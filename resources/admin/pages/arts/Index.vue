<template>
  <app-layout>
    <div class="q-pa-md">
      <div class="mb-4 text-right">
        <q-btn color="primary" label="Create" @click="createDialog" />
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
          />
          <div class="text-h6">Create Art</div>
        </q-card-section>
        <q-separator inset />
        <q-card-section class="q-pt-none">
          <q-form class="q-gutter-md">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label class="q-pb-xs"> Title </q-item-label>
                  <q-input v-model="rowForm.title" filled />
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <div>Description</div>
                  <q-editor v-model="rowForm.description" min-height="5rem" />
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <UploadInput
                    ref="createArtUploadInputRef"
                    :multiple="true"
                    @change="uploadInputChangeHandler"
                  />
                </q-item-section>
              </q-item>
            </q-list>
          </q-form>
        </q-card-section>
        <q-card-section>
          <q-card-actions align="right">
            <q-btn v-close-popup flat label="Cancel" color="primary" />
            <q-btn
              v-close-popup
              label="Save"
              color="primary"
              @click="createRow"
            />
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
          />
          <div class="text-h6">Update Art</div>
        </q-card-section>
        <q-separator inset />
        <q-card-section class="q-pt-none">
          <q-form class="q-gutter-md">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label class="q-pb-xs"> Title </q-item-label>
                  <q-input v-model="rowForm.title" filled />
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section>
                  <div>Description</div>
                  <q-editor v-model="rowForm.description" min-height="5rem" />
                </q-item-section>
              </q-item>

              <q-item>
                <q-item-section>
                  <UploadInput
                    :multiple="true"
                    :init-files="rowForm.files"
                    @change="uploadInputChangeHandler"
                  />
                </q-item-section>
              </q-item>
            </q-list>
          </q-form>
        </q-card-section>
        <q-card-section>
          <q-card-actions align="right">
            <q-btn v-close-popup flat label="Cancel" color="primary" />
            <q-btn
              v-close-popup
              label="Save"
              color="primary"
              @click="updateRow"
            />
          </q-card-actions>
        </q-card-section>
      </q-card>
    </q-dialog>
  </app-layout>
</template>

<script lang="ts" setup>
  import { Col, ArtRowFormType } from '@admin/types/data-table'
  import { shorten } from '@admin/functions'
  import { ref } from 'vue'
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

  const initRowForm: ArtRowFormType = {
    files: [],
    title: null,
    description: '',
    id: null,
  }
  const rowForm = useForm(initRowForm)

  const showEditDialog = ref(false)
  const showCreateDialog = ref(false)
  const loading = ref(false)
  const createArtUploadInputRef = ref(null)

  /* watchEffect(() => { */
  /*       }) */

  let totalCount = props.total!
  let pageSize = props.perPage!
  let pagesCount = totalCount < pageSize ? 1 : Math.ceil(totalCount / pageSize)

  function uploadInputChangeHandler(files) {
    rowForm.files = files
  }

  function createDialog() {
    rowForm.files = []
    rowForm.reset()
    showCreateDialog.value = true
  }

  async function editRow(params) {
    let { row } = params
    showEditDialog.value = true
    rowForm.title = row.title
    rowForm.description = row.description
    rowForm.id = row.id
    rowForm.files = []

    for (const image of row.media) {
      let response = await fetch(image.original_url)
      let data = await response.blob()
      let file = new File([data], image.name, {})
      rowForm.files.push(file)
    }
  }

  function updateRow() {
    rowForm.post(`/admin/arts/${rowForm.id}`, {
      preserveState: true,
      onSuccess: () => rowForm.reset(),
    })
  }

  function createRow() {
    rowForm.id = null
    rowForm.post(`/admin/arts/`, {
      preserveState: false,
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
    Inertia.delete(`/admin/arts/${row.id}`, {
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
<style>
  [for='file2'] {
    cursor: pointer;
  }
</style>
