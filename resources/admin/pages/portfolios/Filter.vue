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
              <q-input v-model="form.name" filled />
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label class="q-pb-xs"> Order </q-item-label>
              <q-input v-model="form.order_number" type="number" filled />
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
  name: null,
  order_number: null,
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
