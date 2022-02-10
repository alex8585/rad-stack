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
        <div class="text-h6">Create Art</div>
      </q-card-section>
      <q-separator inset />
      <q-card-section class="q-pt-none">
        <q-form class="q-gutter-md">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label class="q-pb-xs"> Title </q-item-label>
                <q-input
                  v-model="form.title"
                  :error-message="form.errors.title"
                  :error="!!form.errors.title"
                  filled
                />
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <div>Description</div>
                <q-editor
                  v-model="form.description"
                  :error-message="form.errors.description"
                  :error="!!form.errors.description"
                  min-height="5rem"
                />
                <div v-if="form.errors.description" style="color: red">
                  {{ form.errors.description }}
                </div>
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
          <q-btn label="Save" color="primary" @click="onSend" />
        </q-card-actions>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { ArtRowFormType } from '@admin/types/data-table'

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

const initForm: ArtRowFormType = {
  title: null,
  description: '',
  files: [],
}

const dialogRef = ref()
const form = useForm(initForm)

function uploadInputChangeHandler(files) {
  form.files = files
}

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
