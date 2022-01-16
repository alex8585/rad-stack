<template>
  <app-layout>
    <el-table :data="items" style="width: 100%">
      <el-table-column prop="id" label="ID" />
      <el-table-column prop="title" label="Title" />
      <el-table-column prop="description" label="Description" />

      <el-table-column label="Operations" width="120">
        <template #default>
          <el-button type="text" size="small" @click="handleClick"
            >Detail</el-button
          >
          <el-button type="text" size="small">Edit</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-pagination
      v-model:currentPage="form.page"
      v-model:page-size="form.perPage"
      :page-sizes="[5, 10, 15, 20]"
      layout="total, sizes, prev, pager, next"
      :total="total"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    >
    </el-pagination>
  </app-layout>
</template>

<script lang="ts" setup>
  import { PropType } from 'vue'
  import { PaginatedData, User } from '@admin/types'
  import { Column } from '@admin/types/data-table'
  import { onMounted } from 'vue'
  import { ref } from 'vue'
  import { useForm } from '@inertiajs/inertia-vue3'
  let props = defineProps({
    action: String,
    items: {
      type: Object as PropType<PaginatedData<User>>,
      required: true,
    },
    user: Object as PropType<User>,
    sort: String,
    filter: Object,
    perPage: Number,
    total: Number,
    currentPage: Number,
  })

  const handleClick = () => {
    console.log('click')
  }
  /* const test = computed(() => { */
  /*     console.log(props.action); */
  /*   return "action" */
  /* }) */

  onMounted(() => {
    /* currentPage = props.currentPage */
    console.log(location)
    console.log(props.total)
    console.log(props.currentPage)
    console.log(props.perPage)
  })

  const form = useForm({
    page: parseInt(props.currentPage),
    perPage: parseInt(props.perPage),
    sort: props.sort,
    filter: {},
  })

  const doQuery = () => {
    form.get(location.pathname, {
      preserveState: true,
    })
  }

  function handleSizeChange(newPerPage: number) {
    form.page = 1
    doQuery()
  }

  function handleCurrentChange(newPage: number) {
    /* form.page = newPage */
    doQuery()
  }

  function clicked() {
    console.log(props.action)
  }
  const tableData = [
    {
      date: '2016-05-03',
      name: 'Tom',
      state: 'California',
      city: 'Los Angeles',
      address: 'No. 189, Grove St, Los Angeles',
      zip: 'CA 90036',
      tag: 'Home',
    },
    {
      date: '2016-05-02',
      name: 'Tom',
      state: 'California',
      city: 'Los Angeles',
      address: 'No. 189, Grove St, Los Angeles',
      zip: 'CA 90036',
      tag: 'Office',
    },
    {
      date: '2016-05-04',
      name: 'Tom',
      state: 'California',
      city: 'Los Angeles',
      address: 'No. 189, Grove St, Los Angeles',
      zip: 'CA 90036',
      tag: 'Home',
    },
    {
      date: '2016-05-01',
      name: 'Tom',
      state: 'California',
      city: 'Los Angeles',
      address: 'No. 189, Grove St, Los Angeles',
      zip: 'CA 90036',
      tag: 'Office',
    },
  ]
  /* const canBeUpdated = (item) => { */
  /*   return (item as User).can_be_updated */
  /* } */

  /* const canBeImpersonated = (item) => { */
  /*   return (item as User).can_be_impersonated */
  /* } */
</script>
