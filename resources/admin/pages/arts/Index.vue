<template>
  <app-layout>
    <div class="q-pa-md">
      <q-table
        v-model:pagination="pagination"
        title="Treats"
        :rows="items"
        :columns="columns"
        row-key="id"
        hide-pagination
        :loading="loading"
        @request="onRequest"
      />

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
  </app-layout>
</template>

<script lang="ts" setup>
  import { Col } from '@admin/types/data-table'
  import { ref } from 'vue'
  import { useForm } from '@inertiajs/inertia-vue3'

  let props = defineProps({
    action: String,
    items: Array,
    sortBy: String,
    filter: Object,
    perPage: Number,
    total: Number,
    currentPage: Number,
  })

  let totalCount = props.total!
  let pageSize = props.perPage!
  let pagesCount = totalCount < pageSize ? 1 : Math.ceil(totalCount / pageSize)

  const form = useForm({
    page: props.currentPage,
    perPage: props.perPage,
    sortBy: props.sortBy,
    descending: 0,
    filter: {},
  })

  const loading = ref(false)
  const pagination = ref({
    sortBy: 'id',
    descending: false,
    page: props.currentPage!,
    rowsPerPage: props.perPage,
    rowsNumber: props.total,
  })

  function onPagination(page: string) {
    let curPage = parseInt(page)
    pagination.value.page = curPage
    form.page = curPage
    doQuery()
  }

  function onRequest(params) {
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
      sortable: true,
    },
    {
      align: 'left',
      name: 'description',
      label: 'Description',
      field: 'description',
      sortable: true,
    },
  ]
</script>
