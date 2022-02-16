<template>
  <q-expansion-item
    class="shadow-1 overflow-hidden"
    style=""
    icon="filter_alt"
    label="Filter"
    header-class="bg-primary text-white"
    expand-icon-class="text-white"
  >
    <q-card>
      <q-form class="q-gutter-md">
        <q-list>
          <q-item>
            <q-item-section>
              <q-item-label class="q-pb-xs"> Name </q-item-label>
              <q-input
                v-model="form.name"
                :error-message="form.errors.name"
                :error="!!form.errors.name"
                filled
              />
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label class="q-pb-xs"> Email </q-item-label>
              <q-input
                v-model="form.email"
                :error-message="form.errors.email"
                :error="!!form.errors.email"
                filled
              />
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-select
                v-model="form.active"
                :error-message="form.errors.active"
                :error="!!form.errors.active"
                :options="statuses"
                label="Status"
              />
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-select
                v-model="form.role"
                :error-message="form.errors.role"
                :error="!!form.errors.role"
                :options="rolesArr"
                label="Role"
              />
            </q-item-section>
          </q-item>

          <q-card-section>
            <q-card-actions align="right">
              <q-btn flat label="Reset" color="primary" @click="onReset" />
              <q-btn label="Apply" color="primary" @click="onSend" />
            </q-card-actions>
          </q-card-section>
        </q-list>
      </q-form>
    </q-card>
  </q-expansion-item>
</template>

<script lang="ts" setup>
import { useForm } from '@inertiajs/inertia-vue3'

import { ref, onMounted } from 'vue'

const props = defineProps({
  roles: {
    default: () => {
      return {}
    },
    type: Object,
  },
})

let rolesArr = ref()

const form = useForm({
  name: null,
  email: null,
  role: null,
  active: null,
})

const statuses = [
  { label: 'Disable', value: 0 },
  { label: 'Enable', value: 1 },
]

const emit = defineEmits(['send'])

onMounted(() => {
  rolesArr.value = Object.keys(props.roles).map((k) => {
    return {
      value: k,
      label: props.roles[k],
    }
  })
})
function onReset() {
  form.reset()
  emit('send', form)
}
function onSend() {
  emit('send', form)
}
</script>
