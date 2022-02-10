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
        <div class="text-h6">Create Tag</div>
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
                <q-item-label class="q-pb-xs"> Order number </q-item-label>
                <q-input
                  v-model="form.order_number"
                  :error-message="form.errors.order_number"
                  :error="!!form.errors.order_number"
                  filled
                  type="number"
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
import { TagRowFormType } from '@admin/types/data-table'

const props = defineProps({
  initValues: {
    default: () => [],
    type: Array,
  },
  show: {
    default: false,
    type: Boolean,
  },
})

const emit = defineEmits(['change', 'mount', 'send'])

const isShow = ref(false)

const initForm: TagRowFormType = {
  name: null,
  order_number: null,
}

const dialogRef = ref()
const form = useForm(initForm)

function onSend() {
  emit('send', form)
}

onMounted(() => {
  isShow.value = props.show
  emit('mount')
})

function hide() {
  dialogRef.value.hide()
}

function set(row) {
  for (const key in row) {
    form[key] = row[key]
  }
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
  show,
})
</script>
