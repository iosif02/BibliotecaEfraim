<script setup lang="ts">
import SearchIcon from "@/views/Components/Icons/SearchIcon.vue";
import { ref } from "vue";
import 'primeicons/primeicons.css';

const props = defineProps({
  defaultValue: {
    type: String
  },
  placeholder: {
    type: String
  }
})
let searchValue = props?.defaultValue;
const input = ref();  

const emit = defineEmits(['valueChanged'])

var timer = 0;
var textSearch = () => {
  clearTimeout(timer)

  if(searchValue != ''){
    timer = setTimeout(() => {
      emit.call(this, 'valueChanged', searchValue);
    }, 500)
  }else{
    emit.call(this, 'valueChanged', searchValue);
  }

}

const clearSearch = () => {
  searchValue = '';
  textSearch();
  if(input.value)
    input.value.focus();
};

</script>

<template>
  <div class="search-container">
    <SearchIcon />
    <input v-model="searchValue" class="search" type="text" :placeholder="placeholder" @keyup="textSearch" ref="input">
    <div v-if="searchValue">
      <span @click="clearSearch" class="pi pi-times" style="font-size: 1rem;"></span>
    </div>
  </div>
</template>

<style>
.search-container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  column-gap: .5rem;
  padding: 15px 55px 15px 24px;
  border: none;
  width: 100%;
  height: 50px;

  background: #F6F6F7;
  border-radius: 50px;

  margin-bottom: 20px;
}
.search-container div {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 24px;
  padding: 3px;
  position: absolute;
  right: 25px;
}
.search {
  flex: 1;
  border: none;
  background-color: transparent;
  font-family: 'Roboto-400';
}
.search:focus {
  outline: none;
}
.search::placeholder {
  /* font-family: 'Inter'; */
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 300;
  font-size: 12px;
  line-height: 15px;

  color: #B0ACC2;
}
</style>