<template>
  <app-layout>
    <div class="q-pa-md">
      <div class="mb-4 text-right">
        <q-btn color="primary" label="Create" @click="createDialog" />
      </div>

      <q-table
        v-model:pagination="pagination"
        title="Treats"
        :rows="items"
        :columns="columns"
        row-key="id"
        hide-pagination
        :loading="loading"
        @request="onSort"
      >
        <template #body-cell-thumb="props">
          <q-td :props="props">
            <img :src="props.row.thumb" />
          </q-td>
        </template>

        <template #body-cell-actions="props">
          <q-td :props="props">
            <q-btn
              dense
              round
              flat
              color="grey"
              icon="edit"
              @click="editRow(props)"
            ></q-btn>
            <q-btn
              dense
              round
              flat
              color="grey"
              icon="delete"
              @click="deleteConfirm(props)"
            ></q-btn>
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

    <q-dialog v-model="showCreateDialog">
      <q-card style="width: 600px; max-width: 60vw">
        <q-card-section>
          <q-btn
            v-close-popup
            round
            flat
            dense
            icon="close"
            class="float-right"
            color="grey-8"
          ></q-btn>
          <div class="text-h6">Create Tag</div>
        </q-card-section>
        <q-separator inset></q-separator>
        <q-card-section class="q-pt-none">
          <q-form class="q-gutter-md">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label class="q-pb-xs">Name</q-item-label>
                  <q-input v-model="rowForm.name" />
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label class="q-pb-xs">Order number</q-item-label>
                  <q-input
                    v-model="rowForm.order_number"
                    type="number"
                    filled
                  />
                </q-item-section>
              </q-item>
            </q-list>
          </q-form>
        </q-card-section>
        <q-card-section>
          <q-card-actions align="right">
            <q-btn v-close-popup flat label="Cancel" color="primary"></q-btn>
            <q-btn
              v-close-popup
              label="Save"
              color="primary"
              @click="createRow"
            ></q-btn>
          </q-card-actions>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showEditDialog">
      <q-card style="width: 600px; max-width: 60vw">
        <q-card-section>
          <q-btn
            v-close-popup
            round
            flat
            dense
            icon="close"
            class="float-right"
            color="grey-8"
          ></q-btn>
          <div class="text-h6">Update Tag</div>
        </q-card-section>
        <q-separator inset></q-separator>
        <q-card-section class="q-pt-none">
          <q-form class="q-gutter-md">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label class="q-pb-xs">Name</q-item-label>
                  <q-input v-model="rowForm.name" filled />
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section>
                  <q-item-label class="q-pb-xs">Order number</q-item-label>
                  <q-input
                    v-model="rowForm.order_number"
                    type="number"
                    filled
                  />
                </q-item-section>
              </q-item>
            </q-list>
          </q-form>
        </q-card-section>
        <q-card-section>
          <q-card-actions align="right">
            <q-btn v-close-popup flat label="Cancel" color="primary"></q-btn>
            <q-btn
              v-close-popup
              label="Save"
              color="primary"
              @click="updateRow"
            ></q-btn>
          </q-card-actions>
        </q-card-section>
      </q-card>
    </q-dialog>
  </app-layout>
</template>

<script lang="ts" setup>
  import { Col, TagRowFormType } from '@admin/types/data-table'
  import { shorten } from '@admin/functions'
  import { ref, onMounted } from 'vue'
  import { useForm } from '@inertiajs/inertia-vue3'
  import { useQuasar } from 'quasar'
  import { Inertia } from '@inertiajs/inertia'

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
      format: (val) => shorten(val, 3, ''),
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
  })

  const form = useForm({
    page: props.currentPage,
    perPage: props.perPage,
    sortBy: props.sortBy,
    descending: 0,
    filter: {},
  })

  const initRowForm: TagRowFormType = {
    name: null,
    order_number: '',
    id: null,
  }
  const rowForm = useForm(initRowForm)

  const showEditDialog = ref(false)
  const showCreateDialog = ref(false)
  const loading = ref(false)

  let totalCount = props.total!
  let pageSize = props.perPage!
  let pagesCount = totalCount < pageSize ? 1 : Math.ceil(totalCount / pageSize)

  function createDialog() {
    rowForm.reset()
    showCreateDialog.value = true
  }

  async function editRow(params) {
    let { row } = params
    showEditDialog.value = true
    rowForm.name = row.name
    rowForm.order_number = row.order_number
    rowForm.id = row.id
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

  function createRow() {
    rowForm.id = null
    rowForm.post(`/admin/tags/`, {
      preserveState: false,
    })
  }

  function updateRow() {
    console.log(rowForm)
    rowForm.post(`/admin/tags/${rowForm.id}`, {
      preserveState: true,
      onSuccess: () => rowForm.reset(),
    })
  }

  function deleteRow(params) {
    let { row } = params
    Inertia.delete(`/admin/tags/${row.id}`, {
      preserveState: false,
    })
  }

  function onPagination(page: string) {
    let curPage = parseInt(page)
    pagination.value.page = curPage
    form.page = curPage
    doQuery()
  }

  function onSort(params) {
    const { sortBy } = params.pagination

    pagination.value.descending = !pagination.value.descending

    if (sortBy) {
      pagination.value.sortBy = sortBy
      form.sortBy = sortBy
    }

    form.descending = pagination.value.descending ? 1 : 0
    doQuery()
  }

  const doQuery = () => {
    loading.value = true
    form.get(location.pathname, {
      preserveState: true,
      onSuccess: () => {
        loading.value = false
      },
    })
  }
</script>
