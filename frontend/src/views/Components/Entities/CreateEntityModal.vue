<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import type AuthorModel from '@/models/entities/AuthorModel';
import type { PropType } from 'vue';
import type CategoryModel from '@/models/entities/CategoryModel';
import type PublisherModel from '@/models/entities/PublisherModel';
import AuthorFormComponent from '@/views/Components/Entities/AuthorFormComponent.vue';
import CategoryFormComponent from '@/views/Components/Entities/CategoryFormComponent.vue';
import PublisherFormComponent from '@/views/Components/Entities/PublisherFormComponent.vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  entity: {
    type:  Object as PropType<AuthorModel|CategoryModel|PublisherModel>,
    required: true
  },
  typeEntity: {
    type: String,
    required: true
  }
})

const store = useEntitiesStore();
const emit = defineEmits(['createEntity', 'hideModal'])

var onSubmit = (entity: any) => {
    if(props.typeEntity == 'Author'){
        store.createAuthor(entity)
        .then(result => {
            if(result){
                emit.call(this, 'createEntity', result);
                store.fetchEntities();
            }
        })
        .finally(() => {
            emit.call(this, 'hideModal');
        });
    }

    if(props.typeEntity == 'Category'){
        store.createCategory(entity)
        .then(result => {
            if(result){
                emit.call(this, 'createEntity', result);
                store.fetchEntities();
            }
        })
        .finally(() => {
            emit.call(this, 'hideModal');
        });
    }

    if(props.typeEntity == 'Publisher'){
        store.createPublisher(entity)
        .then(result => {
            if(result){
                emit.call(this, 'createEntity', result);
                store.fetchEntities();
            }
        })
        .finally(() => {
            emit.call(this, 'hideModal');
        });
    }
};

</script>

<template>
    <div class="overlay">
        <div class="modal">
            <p class="modal-title">{{ props.title }}</p>

            <AuthorFormComponent
                v-if="props.typeEntity == 'Author'"
                :initial-value="(props.entity as AuthorModel)"
                @submit="onSubmit"
            />

            <CategoryFormComponent 
                v-if="props.typeEntity == 'Category'"
                :initial-value="(props.entity as CategoryModel)"
                @submit="onSubmit"
            />

            <PublisherFormComponent
                v-if="props.typeEntity == 'Publisher'"
                :initial-value="(props.entity as PublisherModel)"
                @submit="onSubmit"
            />

            <button class="btn w-100 cancel" @click="$emit('hideModal')">Cancel</button>
        </div>
    </div>
    
    <Loading v-if="store.isLoading" />
</template>

<style scoped>
.overlay {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9;

    width: 100%;
    height: 100%;
    position: fixed;
    background-color: rgba(60, 60, 60, 0.5);
}
.modal {
    width: 90%;
    border-radius: 12px;
    background-color: white;
    padding: 20px 16px 16px;    
}
.modal-title{
    font-family: 'Arial';
    font-style: normal;
    font-weight: 600;
    font-size: 18px;
    line-height: 28px;
    text-align: center;
    text-transform: capitalize;
    color: #101828;
}
.cancel{
    border: 1px solid #D0D5DD;
    background-color: white;
    color: #344054;
    margin: 0;
}
</style>