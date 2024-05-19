<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import router from '@/router';
import AuthorFormComponent from '@/views/Components/Entities/AuthorFormComponent.vue';

const props = defineProps({
  id: String
})

if(!props.id || props.id == '0' || !parseInt(props.id)){
  router.back();
}

const store = useEntitiesStore();
store.fetchSelectedAuthor(props.id || '');

var onSubmit = (author: any) => {
  author.authorId = props.id
  store.updateAuthor(author)
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
    <GoBack goBackText="Edit Author"/>
	</div>

  <AuthorFormComponent 
    :initialValue="store.author"
    @submit="onSubmit"
  />
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>