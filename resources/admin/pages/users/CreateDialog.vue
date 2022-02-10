<template>
  <q-dialog ref="dialogRef" v-model="isShow">
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
        <div class="text-h6">Create User</div>
      </q-card-section>
      <q-separator inset />
      <q-card-section class="q-pt-none">
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
                <q-item-label class="q-pb-xs"> Password </q-item-label>
                <q-input
                  v-model="form.password"
                  :error-message="form.errors.password"
                  :error="!!form.errors.password"
                  type="password"
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
          </q-list>
        </q-form>
      </q-card-section>
      <q-card-section>
        <q-card-actions align="right">
          <q-btn v-close-popup flat label="Cancel" color="primary" />
          <q-btn label="Save" color="primary" @click="onSend" />
        </q-card-actions>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { UserRowFormType } from '@admin/types/data-table'

const props = defineProps({
  initValues: {
    default: () => [],
    type: Array,
  },
  show: {
    default: false,
    type: Boolean,
  },
  roles: {
    default: () => {
      return {}
    },
    type: Object,
  },
})

const statuses = [
  { label: 'Disable', value: 0 },
  { label: 'Enable', value: 1 },
]
const emit = defineEmits(['change', 'mount', 'send'])

const isShow = ref(false)

const initForm: UserRowFormType = {
  name: null,
  password: null,
  active: null,
  role: null,
  email: null,
}

let rolesArr = ref([])
const form = useForm(initForm)
const dialogRef = ref()

function onSend() {
  emit('send', form)
}

onMounted(() => {
  rolesArr.value = Object.keys(props.roles).map((k) => {
    return {
      value: k,
      label: props.roles[k],
    }
  })
  isShow.value = props.show
  emit('mount')
})

function set(row) {
  for (const key in row) {
    if (['name', 'email', 'password'].includes(key)) {
      form[key] = row[key]
    }
  }

  const status = statuses.find((e) => e.value == row.active)

  const role = rolesArr.value.find((e) => e.value == row.role)

  form.active = status
  form.role = role
}

function hide() {
  dialogRef.value.hide()
}

function reset() {
  form.clearErrors()
  set(initForm)
  emit('change', form)
}

function show() {
  isShow.value = true
}

defineExpose({
  reset,
  hide,
  set,
  show,
})
</script>
