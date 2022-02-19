<template>
  <auth-layout>
    <h4 style="text-align: center">Reset password</h4>
    <q-form class="q-gutter-md">
      <q-list>
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
        <q-item>
          <q-item-section>
            <q-item-label class="q-pb-xs">Password confirm</q-item-label>
            <q-input
              v-model="form.password_confirmation"
              :error-message="form.errors.password_confirmation"
              :error="!!form.errors.password_confirmation"
              type="password"
              filled
            />
          </q-item-section>
        </q-item>
      </q-list>
      <q-card-section>
        <q-card-actions>
          <q-btn label="Reset Password" color="primary" @click="onSend" />
        </q-card-actions>
      </q-card-section>
    </q-form>
  </auth-layout>
</template>

<script lang="ts" setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { useTitle } from '@admin/features/helpers'
useTitle('Reset password')

const props = defineProps({
  token: String,
  email: String,
})
const initForm = {
  token: props.token,
  email: props.email,
  password: null,
  password_confirmation: null,
}

const form = useForm(initForm)

function onSend() {
  //form.token = props.token
  //form.email = props.email
  form.post(route('password.update'), {})
}
</script>
