<template>
  <auth-layout>
    <h4 style="text-align: center">Forgot password</h4>
    <div class="mb-4 text-sm text-gray-600">
      {{
        $t(
          'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.'
        )
      }}
    </div>

    <q-form class="q-gutter-md">
      <q-list>
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
useTitle('Forgot password')
defineProps({
  status: String,
})

const initForm = {
  email: null,
}

const form = useForm(initForm)

function onSend() {
  form.post(route('password.email'), {})
}
</script>
