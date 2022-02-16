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
              <q-item-label class="q-pb-xs"> Title </q-item-label>
              <q-input v-model="form.title" filled />
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label class="q-pb-xs"> Description </q-item-label>
              <q-input v-model="form.description" filled />
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

const form = useForm({
  title: null,
  description: null,
})

const emit = defineEmits(['send'])

function onReset() {
  form.reset()
  emit('send', form)
}
function onSend() {
  emit('send', form)
}
</script>
