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

        <q-toolbar-title> Quasar App </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered class="bg-grey-2">
      <q-list>
        <q-item-label header>Admin Menu</q-item-label>

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
        <slot></slot>
      </main>
    </q-page-container>
  </q-layout>
</template>

<script>
  import { ref } from 'vue'
  import { mainNav, isTitle, isLink } from '@admin/_nav'
  export default {
    name: 'MyLayout',
    setup() {
      const leftDrawerOpen = ref(false)
      function toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
      return {
        leftDrawerOpen,
        toggleLeftDrawer,
        mainNav,
        isTitle,
        isLink,
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
