<template>
  <app-layout>
    <div class="q-pa-md">
      <div class="head-buttons">
        <div class="q-pa-md" style="width: 350px">
          <Filter @send="onFilterSend" />
        </div>
        <div>
          <q-input
            v-model="queryForm.filter.q"
            label="Search"
            @keyup.enter="onSearch"
          >
            <template #append>
              <q-icon name="search" class="cursor-pointer" @click="onSearch" />
            </template>
          </q-input>
        </div>
        <div class="q-pa-md text-right">
          <q-btn color="primary" label="Create" @click="createDialog" />
        </div>
      </div>

      <q-table
        v-model:pagination="pagination"
        title="Arts"
        :rows="items"
        :columns="columns"
        row-key="id"
        hide-pagination
        :loading="loading"
        @request="onSort"
      >
        <template #body-cell-thumb="params">
          <q-td :props="params">
            <img :src="params.row.thumb" />
          </q-td>
        </template>

        <template #body-cell-actions="params">
          <q-td :props="params">
            <q-btn
              dense
              round
              flat
              color="grey"
              icon="edit"
              @click="editRow(params)"
            />
            <q-btn
              dense
              round
              flat
              color="grey"
              icon="delete"
              @click="deleteConfirm(params)"
            />
          </q-td>
        </template>
      </q-table>

      <div class="q-pa-lg flex flex-center">
        <q-pagination
          v-model="pagination.page"
          :max="pagesCount"
          direction-links
          boundary-links
          :max-pages="5"
          @update:model-value="onPagination"
        />
      </div>
    </div>

    <CreateDialog ref="createDialRef" @send="createSendHandler" />
    <EditDialog ref="editDialRef" @send="editSendHandler" />
  </app-layout>
</template>

<script lang="ts" setup>
import { Col } from '@admin/types/data-table'
import Filter from './Filter.vue'
import { shorten } from '@admin/functions'
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { useQuasar } from 'quasar'

import { usePages } from '../../composables/pages'
import { Inertia } from '@inertiajs/inertia'
import CreateDialog from './CreateDialog.vue'
import EditDialog from './EditDialog.vue'
import { useTitle } from '@admin/features/helpers'
useTitle('Arts')

const currentUrl = route(route().current())
const $q = useQuasar()

let props = defineProps({
  action: String,
  items: Array,
  sortBy: String,
  filter: Object,
  perPage: Number,
  total: Number,
  currentPage: Number,
})

const columns: Array<Col> = [
  {
    name: 'id',
    required: true,
    label: 'ID',
    align: 'left',
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: 'title',
    align: 'left',
    label: 'Title',
    field: 'title',
    format: (val) => shorten(val, 3, ''),
    sortable: true,
  },
  {
    align: 'left',
    name: 'description',
    label: 'Description',
    field: 'description',
    format: (val) => shorten(val, 5),
    sortable: true,
  },
  {
    name: 'thumb',
    sortable: false,
    label: 'Img',
    field: 'thumb',
    align: 'center',
  },

  {
    name: 'actions',
    sortable: false,
    label: 'Actions',
    field: '',
    align: 'center',
  },
]

const pagesCount = usePages(props)
const pagination = ref({
  sortBy: 'id',
  descending: false,
  page: props.currentPage!,
  rowsPerPage: props.perPage,
  rowsNumber: props.total,
})

const queryForm = useForm({
  page: props.currentPage,
  perPage: props.perPage,
  sortBy: props.sortBy,
  descending: 0,
  filter: {
    q: null,
  },
})

const createDialRef = ref()
const editDialRef = ref()

const loading = ref(false)

function createDialog() {
  createDialRef.value.reset()
  createDialRef.value.show()
}

function editRow(params) {
  let { row } = params
  editDialRef.value.clearErrors()
  editDialRef.value.set(row)
  editDialRef.value.show()
}

function createSendHandler(form) {
  form.post(currentUrl, {
    onSuccess: () => {
      createDialRef.value.hide()
    },
  })
}

function editSendHandler(form) {
  form.post(`${currentUrl}/${form.id}`, {
    onSuccess: () => {
      editDialRef.value.hide()
    },
  })
}

function deleteConfirm(params) {
  $q.dialog({
    title: 'Delete confirmation',
    message: 'Are you sure you want to delete this item?',
    cancel: true,
  }).onOk(() => {
    deleteRow(params)
  })
}

function deleteRow(params) {
  let { row } = params
  Inertia.delete(`${currentUrl}/${row.id}`, {
    preserveState: false,
  })
}

function onFilterSend(form) {
  queryForm.filter = form.data()
  doQuery()
}

function onPagination(page: string) {
  let curPage = parseInt(page)
  pagination.value.page = curPage
  queryForm.page = curPage
  doQuery()
}

function onSearch() {
  doQuery()
}

function onSort(params) {
  const { sortBy } = params.pagination

  pagination.value.descending = !pagination.value.descending

  if (sortBy) {
    pagination.value.sortBy = sortBy
    queryForm.sortBy = sortBy
  }

  queryForm.descending = pagination.value.descending ? 1 : 0
  doQuery()
}

const doQuery = () => {
  loading.value = true
  queryForm.get(location.pathname, {
    preserveState: true,
    onSuccess: () => {
      loading.value = false
    },
  })
}
</script>
<style>
[for='file2'] {
  cursor: pointer;
}
</style>
