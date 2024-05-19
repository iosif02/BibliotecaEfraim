<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import router from '@/router';
import AuthorFormComponent from '@/views/Components/Entities/AuthorFormComponent.vue';

const store = useEntitiesStore();

var onSubmit = (author: any) => {
  store.createAuthor(author)
  .then(result => {
    if(result){
      router.back();
    }
  });
}
</script>

<template>
  <Loading v-if="store.isLoading" />

	<div>
    <GoBack goBackText="Add Author"/>
	</div>

  <AuthorFormComponent 
    @submit="onSubmit"
  />
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>