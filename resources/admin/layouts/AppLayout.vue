<template>
  <q-layout view="lHh Lpr lFf" class="bg-white">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          aria-label="Menu"
          icon="menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title> Admin panel </q-toolbar-title>
        <div class="q-pa-md">
          <q-btn-dropdown color="primary" label="Account">
            <q-list>
              <template #content>
                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                  {{ $t('Manage Account') }}
                </div>

                <dropdown-link :href="route('admin.profile.show')" icon="user">
                  {{ $t('Profile') }}
                </dropdown-link>

                <div class="border-t border-gray-100" />

                <!-- Authentication -->
                <dropdown-link
                  v-if="$page.props.auth.is_impersonating"
                  icon="lock-open"
                  class="bg-yellow-300 hover:bg-yellow-500"
                  @click="stopImpersonate"
                >
                  {{ $t('Stop impersonate') }}
                </dropdown-link>

                <!-- Authentication -->
                <dropdown-link icon="logout" @click="logout">
                  {{ $t('Log Out') }}
                </dropdown-link>
              </template>
              <dropdown-link :href="route('admin.profile.show')" icon="user">
                <q-item>
                  <q-item-section>
                    <q-item-label>
                      {{ $t('Profile') }}
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </dropdown-link>
              <dropdown-link icon="logout" @click="logout">
                <q-item>
                  <q-item-section>
                    <q-item-label>
                      {{ $t('Log Out') }}
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </dropdown-link>
            </q-list>
          </q-btn-dropdown>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered class="bg-grey-2">
      <q-list>
        <q-item-label header> Menu </q-item-label>

        <div v-for="(link, i) in mainNav" :key="i" class="mb-4 group">
          <!--  <inertia-link
                        v-if="isLink(link)"
                        class="flex items-center py-3"
                        :href="link.href"
                        :class="{ active: link.active() }"
                    >
                        <component :is="`${link.icon}-icon-solid`" class="w-5 h-5 mr-2" />
                        {{ link.text }}
                    </inertia-link> -->

          <inertia-link
            v-if="isLink(link)"
            class="flex items-center"
            :href="link.href"
            :class="{ active: link.active() }"
          >
            <q-item>
              <q-item-section avatar>
                <q-icon :name="`${link.newicon}`" />

                <!-- <component :is="`${link.icon}-icon-solid`" class="w-5 h-5 mr-2" /> -->
              </q-item-section>
              <q-item-section>
                {{ link.text }}
              </q-item-section>
            </q-item>
          </inertia-link>

          <h3
            v-if="isTitle(link)"
            class="text-primary-300 text-xs uppercase font-bold pt-4 pb-2 border-primary-300 border-b-1"
          >
            {{ link.title }}
          </h3>
        </div>
      </q-list>
    </q-drawer>

    <q-page-container>
      <main class="px-4 py-8 md:p-8">
        <slot />
      </main>
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref } from 'vue'
import { mainNav, isTitle, isLink } from '@admin/_nav'

import { Inertia } from '@inertiajs/inertia'
export default {
  name: 'MyLayout',
  setup() {
    const leftDrawerOpen = ref(false)
    function toggleLeftDrawer() {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }

    function logout() {
      Inertia.post(route('logout'))
    }

    return {
      leftDrawerOpen,
      toggleLeftDrawer,
      mainNav,
      isTitle,
      isLink,
      logout,
    }
  },
}
</script>

<style lang="postcss" scoped>
a {
  &:hover {
    background-color: #dfdfdf !important;
  }

  &.active {
    background-color: #dfdfdf !important;
  }
}
</style>
