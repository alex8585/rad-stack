<template>
  <app-layout>
    <button @click="clicked">BTN</button>

    <ul id="example-1">
      <li v-for="item in items" :key="item.id">
        {{ item.title }}
      </li>
    </ul>
  </app-layout>
</template>

<script lang="ts" setup>
  import { PropType } from 'vue'
  import { PaginatedData, User } from '@admin/types'
  import { Column } from '@admin/types/data-table'
  import { onMounted } from 'vue'
  let props = defineProps({
    action: String,
    items: {
      type: Object as PropType<PaginatedData<User>>,
      required: true,
    },
    user: Object as PropType<User>,
    sort: String,
    filter: Object,
  })

  /* const test = computed(() => { */
  /*     console.log(props.action); */
  /*   return "action" */
  /* }) */

  onMounted(() => {
    console.log(props.items)
  })

  function clicked() {
    console.log(props.action)
  }

  const columns: (string | Column)[] = [
    {
      field: 'id',
      width: 40,
      numeric: true,
      sortable: true,
    },
    {
      field: 'name',
      sortable: true,
      searchable: true,
    },
    {
      field: 'email',
      searchable: true,
      type: 'email',
    },
    {
      field: 'active',
      type: 'switch',
      searchable: true,
    },
    {
      field: 'role',
      type: 'select',
      props: { choices: 'roles' },
      searchable: true,
    },
    {
      field: 'last_login_at',
      type: 'date',
      props: { format: 'dd/MM/yyyy HH:mm:ss' },
      sortable: true,
      centered: true,
    },
    {
      field: 'created_at',
      type: 'date',
      sortable: true,
      centered: true,
    },
    {
      field: 'updated_at',
      type: 'date',
      sortable: true,
      centered: true,
    },
    'row-action',
  ]

  /* const canBeUpdated = (item) => { */
  /*   return (item as User).can_be_updated */
  /* } */

  /* const canBeImpersonated = (item) => { */
  /*   return (item as User).can_be_impersonated */
  /* } */
</script>
