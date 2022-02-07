<template>
  <app-layout>
    <div class="q-pa-md">
      <div class="mb-4 text-right">
        <q-btn color="primary" label="Create" @click="createDialog" />
      </div>

      <q-table
        v-model:pagination="pagination"
        title="Portfolios"
        :rows="items"
        :columns="columns"
        row-key="id"
        hide-pagination
        :loading="loading"
        @request="onSort"
      >
        <template #body-cell-thumb="params">
          <q-td :props="params">
            <div class="img-wrapp">
              <img class="image" :src="params.row.thumb" />
            </div>
          </q-td>
        </template>

        <template #body-cell-tags="params">
          <q-td :props="params">
            <span v-for="tag in params.row.tags" :key="tag.id">
              &nbsp; {{ tag.name }}
            </span>
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

    <CreateDialog ref="createDialRef" :tags="tags" @send="createSendHandler" />
    <EditDialog ref="editDialRef" :tags="tags" @send="editSendHandler" />
  </app-layout>
</template>

<script lang="ts" setup>
  import { Col } from '@admin/types/data-table'
  import { shorten, getPageCount } from '@admin/functions'
  import { ref } from 'vue'
  import { useForm } from '@inertiajs/inertia-vue3'
  import { useQuasar } from 'quasar'
  import { Inertia } from '@inertiajs/inertia'
  import CreateDialog from './CreateDialog.vue'
  import EditDialog from './EditDialog.vue'
  const $q = useQuasar()

  let props = defineProps({
    action: String,
    items: Array,
    sortBy: String,
    filter: Object,
    perPage: Number,
    total: Number,
    currentPage: Number,
    tags: Array,
  })

  const currentUrl = route(route().current())
  let pagesCount = getPageCount(props.total, props.perPage)

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
      name: 'order_number',
      align: 'left',
      label: 'Order',
      field: 'order_number',
      sortable: true,
    },
    {
      name: 'thumb',
      align: 'left',
      label: 'Image',
      field: 'thumb',
      sortable: false,
    },
    {
      name: 'tags',
      align: 'left',
      label: 'Tags',
      field: 'tags',
      sortable: false,
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
  })

  const paginateForm = useForm({
    page: props.currentPage,
    perPage: props.perPage,
    sortBy: props.sortBy,
    descending: 0,
    filter: {},
  })

  const loading = ref(false)
  const createDialRef = ref()
  const editDialRef = ref()

  console.log(props)
  function createDialog() {
    createDialRef.value.reset()
    createDialRef.value.show()
  }

  function editRow(params) {
    let { row } = params
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
    form.post(currentUrl)
  }

  function editSendHandler(form) {
    form.post(`${currentUrl}/${form.id}`)
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
    paginateForm.page = curPage
    doQuery()
  }

  function onSort(params) {
    const { sortBy } = params.pagination

    pagination.value.descending = !pagination.value.descending

    if (sortBy) {
      pagination.value.sortBy = sortBy
      paginateForm.sortBy = sortBy
    }

    paginateForm.descending = pagination.value.descending ? 1 : 0
    doQuery()
  }

  const doQuery = () => {
    loading.value = true
    paginateForm.get(location.pathname, {
      preserveState: true,
      onSuccess: () => {
        loading.value = false
      },
    })
  }
</script>
<style scoped>
  .image {
    height: 100%;
    width: 100%;
  }

  .img-wrapp {
    position: relative;
    margin-right: 10px;
    margin-bottom: 10px;
    width: 100px;
    height: 100px;
  }
</style>
