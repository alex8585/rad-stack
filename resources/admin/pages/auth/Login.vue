<template>
  <auth-layout>
    <h4 style="text-align: center">Login</h4>
    <q-form class="q-gutter-md">
      <q-list>
        <q-item>
          <q-item-section>
            <q-item-label class="q-pb-xs"> Email </q-item-label>
            <q-input
              v-model="form.email"
              type="email"
              :error-message="form.errors.email"
              :error="!!form.errors.email"
              filled
            />
          </q-item-section>
        </q-item>
        <q-item>
          <q-item-section>
            <q-item-label class="q-pb-xs">Password</q-item-label>
            <q-input
              v-model="form.password"
              :error-message="form.errors.password"
              :error="!!form.errors.password"
              type="password"
              filled
            />
          </q-item-section>
        </q-item>
      </q-list>
      <q-card-section>
        <q-card-actions align="right">
          <q-btn label="Login" color="primary" @click="onSend" />
        </q-card-actions>
      </q-card-section>
    </q-form>
  </auth-layout>
</template>

<script lang="ts" setup>
import { useForm } from '@inertiajs/inertia-vue3'

import { useTitle } from '@admin/features/helpers'
useTitle('Login')
defineProps({
  canRegister: Boolean,
  status: String,
})

const initForm = {
  email: null,
  password: null,
}

const form = useForm(initForm)

function onSend() {
  form.post(route('login'), {})
}
</script>
