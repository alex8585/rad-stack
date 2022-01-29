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
        ></q-btn>
        <div class="text-h6">Create Portfolio</div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section class="q-pt-none">
        <q-form class="q-gutter-md">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label class="q-pb-xs">Name</q-item-label>
                <q-input v-model="form.name" filled />
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label class="q-pb-xs">Url</q-item-label>
                <q-input v-model="form.url" filled />
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label class="q-pb-xs">Order number</q-item-label>
                <q-input v-model="form.order_number" filled type="number" />
              </q-item-section>
            </q-item>
          </q-list>
        </q-form>
      </q-card-section>
      <q-card-section>
        <q-card-actions align="right">
          <q-btn v-close-popup flat label="Cancel" color="primary"></q-btn>
          <q-btn
            v-close-popup
            label="Save"
            color="primary"
            @click="onSend"
          ></q-btn>
        </q-card-actions>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script lang="ts" setup>
  import { ref, onMounted } from 'vue'
  import { useForm } from '@inertiajs/inertia-vue3'
  import { PortfolioRowFormType } from '@admin/types/data-table'

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

  const ititForm: PortfolioRowFormType = {
    name: null,
    url: null,
    order_number: '',
    id: null,
  }

  const form = useForm(ititForm)

  function onSend() {
    emit('send', form)
  }

  onMounted(() => {
    isShow.value = props.show
    emit('mount')
  })

  function set(row) {
    for (const key in row) {
      form[key] = row[key]
    }
  }

  function reset() {
    set(ititForm)
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
