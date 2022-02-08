<template>
  <q-dialog v-model="isShow">
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
                <q-input v-model="form.name" filled />
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-item-label class="q-pb-xs"> Email </q-item-label>
                <q-input v-model="form.email" filled />
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-item-label class="q-pb-xs"> Password </q-item-label>
                <q-input v-model="form.password" type="password" filled />
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-select
                  v-model="form.active"
                  :options="statuses"
                  label="Status"
                />
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-select
                  v-model="form.role"
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
          <q-btn v-close-popup label="Save" color="primary" @click="onSend" />
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
/* console.log(props.roles) */
const emit = defineEmits(['change', 'mount', 'send'])

const isShow = ref(false)

const initForm: UserRowFormType = {
  name: null,
  password: '',
  id: null,
  active: '',
  role: '',
  email: '',
}

let rolesArr = ref([])
const form = useForm(initForm)

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
    form[key] = row[key]
  }
}

function reset() {
  set(initForm)
  emit('change', form)
}

function show() {
  isShow.value = true
}

defineExpose({
  reset,
  show,
})
</script>
