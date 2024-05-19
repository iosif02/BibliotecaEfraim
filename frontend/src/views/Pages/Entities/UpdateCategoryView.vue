<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import CategoryFormComponent from '@/views/Components/Entities/CategoryFormComponent.vue';
import router from '@/router';

const props = defineProps({
  id: String
})

if(!props.id || props.id == '0' || !parseInt(props.id)){
  router.back();
}

const store = useEntitiesStore();
store.fetchSelectedCategory(props.id || '');

var onSubmit = (category: any) => {
  category.categoryId = props.id
  store.updateCategory(category)
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
    <GoBack goBackText="Edit Category"/>
	</div>

  <CategoryFormComponent
    :initial-value="store.category" 
    @submit="onSubmit"
  />  
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>