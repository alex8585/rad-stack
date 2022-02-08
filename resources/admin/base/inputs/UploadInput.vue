<template>
  <div ref="root">
    <div>
      <ul class="flex">
        <li v-for="(file, i) in files" :key="i">
          <div class="img-wrapp">
            <img class="image" :src="imgUrlFromFile(file)" />
            <q-icon
              class="delete-icon"
              name="clear"
              @click="deleteHandler(i)"
            />
          </div>
        </li>
      </ul>
    </div>

    <input
      ref="input"
      style="display: none"
      v-bind="$attrs"
      type="file"
      :multiple="multiple"
      @test="test"
      @change="onChangeHandler"
    />
    <q-btn label="Upload Images" color="primary" @click="choiceFiles" />
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
const props = defineProps({
  initFiles: {
    default: () => [],
    type: Array,
  },
  multiple: {
    default: () => false,
    type: Boolean,
  },
})

onMounted(() => {
  files.value = props.initFiles
  emit('mount')
})

const input = ref()
const files = ref()
const root = ref()

const emit = defineEmits(['change', 'mount'])

function imgUrlFromFile(file) {
  let urlCreator = window.URL || window.webkitURL
  let imageUrl = urlCreator.createObjectURL(file)
  return imageUrl
}

function deleteHandler(index) {
  files.value.splice(index, 1)
  emit('change', files.value)
  /* console.log(files) */
}

function onChangeHandler(e) {
  let curFiles = e.target.files

  if (curFiles.length && !props.multiple) {
    files.value = []
  }

  for (const file of curFiles) {
    files.value.push(file as any)
  }
  /* console.log(files.value) */

  emit('change', files.value)
}

function choiceFiles() {
  input.value.click()
}
const reset = () => {
  files.value = []
}

defineExpose({
  root,
  reset,
})
</script>

<style scoped>
.image {
  height: 100%;
  width: 100%;
}

.delete-icon {
  color: red;
  font-size: 1.8em;
  font-weight: 900;
  position: absolute;
  top: 0px;
  right: 0px;
  cursor: pointer;
}

.img-wrapp {
  position: relative;
  margin-right: 10px;
  margin-bottom: 10px;
  width: 100px;
  height: 100px;
}
</style>
