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
        <div class="text-h6">Create Portfolio</div>
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
                <q-item-label class="q-pb-xs"> Url </q-item-label>
                <q-input
                  v-model="form.url"
                  :error-message="form.errors.url"
                  :error="!!form.errors.url"
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
            <q-item>
              <q-item-section>
                <UploadInput
                  :init-files="form.files"
                  @change="uploadInputChangeHandler"
                />
              </q-item-section>
            </q-item>

            <q-select
              v-model="form.tags"
              filled
              multiple
              :options="options"
              label="Tags"
              style="width: 250px"
            />
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
import {
  OptionType,
  TagType,
  PortfolioRowFormType,
} from '@admin/types/data-table'

const props = defineProps({
  tags: {
    default: () => [],
    type: Array,
  },

  initValues: {
    default: () => [],
    type: Array,
  },
  show: {
    default: false,
    type: Boolean,
  },
})

let options: Array<OptionType> = []
function uploadInputChangeHandler(files) {
  form.files = files
}

const emit = defineEmits(['change', 'mount', 'send'])

const isShow = ref(false)

const dialogRef = ref()
const initForm: PortfolioRowFormType = {
  name: null,
  url: null,
  order_number: '',
  id: null,
  files: [],
  tags: [],
}

const form = useForm(initForm)

function onSend() {
  emit('send', form)
}
function hide() {
  dialogRef.value.hide()
}
function clearErrors() {
  form.clearErrors()
}
function reset() {
  form.clearErrors()
  form.reset()
  emit('change', form)
}
onMounted(() => {
  isShow.value = props.show

  for (const tag of props.tags as Array<TagType>) {
    let option = {
      label: tag.name,
      value: tag.id,
    }
    options.push(option)
  }
  emit('mount')
})

async function set(row) {
  for (const key in row) {
    if (key == 'tags' || key == 'files') {
      continue
    }
    form[key] = row[key]
  }

  form.files = []
  if (row.image) {
    let response = await fetch(row.image)
    var filename = row.image.replace(/^.*[\\/]/, '')
    let data = await response.blob()
    let file = new File([data], filename)
    form.files.push(file)
  }

  form.tags = []
  for (const tag of row.tags as Array<TagType>) {
    let option: OptionType = {
      label: tag.name,
      value: tag.id,
    }
    form.tags.push(option)
  }
}

function show() {
  isShow.value = true
}

defineExpose({
  hide,
  clearErrors,
  reset,
  show,
  set,
})
</script>
