<template>
  <app-layout>
    <div class="q-pa-md">
      <div class="head-buttons">
        <div class="q-pa-md" style="width: 350px">
          <Filter :roles="enums?.roles" @send="onFilterSend" />
        </div>
        <div class="q-pa-md text-right">
          <q-btn color="primary" label="Create" @click="createDialog" />
        </div>
      </div>
      <q-table
        v-model:pagination="pagination"
        title="Users"
        :rows="items"
        :columns="columns"
        row-key="id"
        hide-pagination
        :loading="loading"
        @request="onSort"
      >
        <template #body-cell-active="params">
          <q-td :props="params">
            <div v-if="params.row.active">Enabled</div>
            <div v-else>Disabled</div>
          </q-td>
        </template>

        <template #body-cell-role="params">
          <q-td :props="params">
            <div>{{ enums?.roles[params.row.role] }}</div>
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
          :max="pagination.pagesCount"
          direction-links
          boundary-links
          :max-pages="5"
          @update:model-value="onPagination"
        />
      </div>
    </div>

    <CreateDialog
      ref="createDialRef"
      :roles="enums?.roles"
      @send="createSendHandler"
    />
    <EditDialog
      ref="editDialRef"
      :roles="enums?.roles"
      @send="editSendHandler"
    />
  </app-layout>
</template>
<script lang="ts" setup>
import { Col } from '@admin/types/data-table'
import { shorten, getPageCount } from '@admin/functions'
import { ref, onUpdated } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { useQuasar } from 'quasar'
import { Inertia } from '@inertiajs/inertia'

import Filter from './Filter.vue'
import CreateDialog from './CreateDialog.vue'
import EditDialog from './EditDialog.vue'
const $q = useQuasar()

const currentUrl = route(route().current())
let props = defineProps({
  action: String,
  items: Array,
  sortBy: String,
  filter: Object,
  perPage: Number,
  total: Number,
  currentPage: Number,
  enums: Object,
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
    name: 'name',
    align: 'left',
    label: 'Name',
    field: 'name',
    format: (val) => shorten(val, 3, ''),
    sortable: true,
  },
  {
    name: 'email',
    align: 'left',
    label: 'Email',
    field: 'email',
    format: (val) => shorten(val, 3, ''),
    sortable: true,
  },
  {
    name: 'role',
    align: 'left',
    label: 'Role',
    field: 'role',
    format: (val) => shorten(val, 3, ''),
    sortable: true,
  },
  {
    name: 'active',
    align: 'left',
    label: 'Status',
    field: 'active',
    sortable: true,
  },

  {
    name: 'actions',
    sortable: false,
    label: 'Actions',
    field: '',
    align: 'center',
  },
]

const pagination = ref({
  sortBy: 'id',
  descending: false,
  page: props.currentPage!,
  rowsPerPage: props.perPage,
  rowsNumber: props.total,
  pagesCount: getPageCount(props.total, props.perPage),
})

onUpdated(() => {
  pagination.value.pagesCount = getPageCount(props.total, props.perPage)
})

const queryForm = useForm({
  page: props.currentPage,
  perPage: props.perPage,
  sortBy: props.sortBy,
  descending: 0,
  filter: {},
})

const loading = ref(false)
const createDialRef = ref()
const editDialRef = ref()

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

function deleteConfirm(params) {
  $q.dialog({
    title: 'Delete confirmation',
    message: 'Are you sure you want to delete this item?',
    cancel: true,
  }).onOk(() => {
    deleteRow(params)
  })
}

function createSendHandler(form) {
  Inertia.post(
    currentUrl,
    {
      ...form,
      role: form.role?.value,
      active: form.active?.value,
    },
    {
      onSuccess: () => {
        createDialRef.value.hide()
        createDialRef.value.reset()
      },
      onError: (errors) => {
        form.errors = errors
      },
    }
  )
}

function editSendHandler(form) {
  Inertia.post(
    `${currentUrl}/${form.id}`,
    {
      ...form,
      role: form.role?.value,
      active: form.active?.value,
    },
    {
      onSuccess: () => {
        editDialRef.value.hide()
        editDialRef.value.reset()
      },
      onError: (errors) => {
        form.errors = errors
      },
    }
  )
}

function deleteRow(params) {
  let { row } = params
  Inertia.delete(`${currentUrl}/${row.id}`, {
    preserveState: false,
  })
}

function onPagination(page: string) {
  let curPage = parseInt(page)
  pagination.value.page = curPage
  queryForm.page = curPage
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

function onFilterSend(form) {
  queryForm.filter = form.data()
  doQuery()
}

const doQuery = (preserve = true) => {
  loading.value = true
  queryForm.get(location.pathname, {
    preserveState: preserve,
    onSuccess: () => {
      loading.value = false
    },
  })
}
</script>
<style>
.head-buttons {
  justify-content: space-between;
  display: flex;
  .q-item.bg-primary {
    min-height: 0px;
  }
}
</style>
