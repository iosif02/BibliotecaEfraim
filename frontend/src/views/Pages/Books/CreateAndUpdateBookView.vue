<script setup lang="ts">
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { ref } from 'vue';
import { useBooksStore } from '@/stores/books-store';
import { useEntitiesStore } from '@/stores/entities-store';
import type AuthorModel from '@/models/entities/AuthorModel';
import type CategoryModel from '@/models/entities/CategoryModel';
import type PublisherModel from '@/models/entities/PublisherModel';
import CreateEntityModal from '@/views/Components/Entities/CreateEntityModal.vue'
import router from '@/router';
import { useRoute } from 'vue-router';

const props = defineProps({
  id: String,
})

const entitiesStore = useEntitiesStore();
const booksStore = useBooksStore();

const bookId = ref<any>(props.id);
const route = useRoute();

if(route.name == 'editBook'){
    if(!bookId.value || bookId.value == '0' || !parseInt(bookId.value)) {
        router.back();
    }

    booksStore.fetchBookDetails(bookId.value);
}

if (route.name == 'createBook') {
    booksStore.bookDetails.image = ''
}

if(!entitiesStore.entities.publishers.length) {
    entitiesStore.fetchEntities();
}

const validateForm = yup.object({
    image: yup.string().nullable(),
    title: yup.string().required(),
    year: yup.number().typeError('Quantity must be a number').required(),
    quantity: yup.number().typeError('Quantity must be a number').required(),
    price: yup.string().nullable(),
    publisher: yup.object().typeError('Must be one in the dropdown').required(),
    authors: yup.array<AuthorModel>().typeError('must be one in the dropdown').required(),
    category: yup.object().typeError('Must be one in the dropdown').required(),
});

const imgSrc = ref<String|ArrayBuffer|null|undefined>("");
const imgObj = ref<any>(null);
var onFile = (e: any) => {
    const files = e.target.files;
    if (!files.length) return;

    imgObj.value = files[0];

    const reader = new FileReader();
    reader.readAsDataURL(files[0]);
    reader.onload = (e) => {
        imgSrc.value = e.target?.result;
    }
}

const filteredAuthors = ref<Array<AuthorModel>>([]);
const filteredCategories = ref<Array<CategoryModel>>([]);
const filteredPublishers = ref<Array<PublisherModel>>([]);

const selectedAuthors = ref<Array<AuthorModel>>([]);
const selectedCategory = ref<CategoryModel>();
const selectedPublisher = ref<PublisherModel>();

var searchAuthors = (event: any) => {
    filteredAuthors.value = entitiesStore.entities.authors.filter(x => x.name.toLowerCase().includes(event.query.toLowerCase())).filter(x => !selectedAuthors.value.some(x2 => x.id === x2.id));
    if(filteredAuthors.value.length == 0){
        filteredAuthors.value = [{id: 0, name: "Result not found! Tap to create!", book_count: 0, pivot: { author_id: 0, book_id: 0 }}]
        entity = {id: 0, name: event.query, book_count: 0, pivot: { author_id: 0, book_id: 0 }}
    }
}
var searchCategories = (event: any) => {
    filteredCategories.value = entitiesStore.entities.categories.filter(x => x.name.toLowerCase().includes(event.query.toLowerCase()));
    if(filteredCategories.value.length == 0){
        filteredCategories.value = [{id: 0, name: "Result not found! Tap to create!", description: '' , number: 0 ,book_count: 0}]
        entity = {id: 0, name: event.query, description: '' , number: 0 ,book_count: 0}
    }
}
var searchPublishers = (event: any) => {
    filteredPublishers.value = entitiesStore.entities.publishers.filter(x => x.name.toLowerCase().includes(event.query.toLowerCase()));
    if(filteredPublishers.value.length == 0){
        filteredPublishers.value = [{id: 0, name: "Result not found! Tap to create!", city: '', book_count: 0}]
        entity = {id: 0, name: event.query, city: '', book_count: 0}
    }
}

var onSubmit = (book: any) => {
    let formData = new FormData()
    formData.append('category_id', book.category?.id)
    formData.append('publisher_id', book.publisher?.id)
    formData.append('bookId', bookId.value)

    book.authors.map((x: any) => {
        formData.append('authors[]', x.id)
    });
    
    if(imgObj.value) formData.append('imageFile', imgObj.value)

    formData.append('title', book?.title)
    formData.append('price', book?.price)
    formData.append('year', book?.year)
    formData.append('quantity', book?.quantity)
    formData.append('is_marked', book?.is_marked)
    
    if(route.name == 'editBook'){
        return booksStore.updateBook(formData).then(result => {
            if(result){
                if(booksStore.homepage.searchModel.title != "")
                    booksStore.searchHomeBooks();

                booksStore.fetchHomepage()
                router.back();
            }
        });
    }

    return booksStore.createBook(formData).then(result => {
        if(result){
            if(booksStore.homepage.searchModel.title != "")
                booksStore.searchHomeBooks();
            router.back()
        }
    });
}

let focusedElement: any = ref(null)

var focusInput = () => {
    if(focusedElement) focusedElement?.target?.focus();
    focusedElement.value = null;
}

var blurInput = () => {
    if(focusedElement) {
        setTimeout(() => {
            focusedElement?.target?.blur();   
        }, 1);
    }
    focusedElement.value = null;
}

function onFocusElement(e: any) {
    focusedElement = e;
}

let typeEntity = ''

let entity: AuthorModel|CategoryModel|PublisherModel = {
    id: 0, 
    number: 0, 
    description: '', 
    book_count: 0, 
    name:''
};

const showModal = ref<boolean>(false);

var submit = (entity: any) => {
    if(typeEntity == 'Author'){
        let newArray = [entity, ...selectedAuthors.value];
        selectedAuthors.value = newArray;
    }

    if(typeEntity == 'Category'){
        selectedCategory.value = entity;
    }

    if(typeEntity == 'Publisher'){
        selectedPublisher.value = entity;
    }
}
</script>

<template>
    
    <Loading v-if="booksStore.isLoading || entitiesStore.isLoading" />

    <GoBack :go-back-text="route.name == 'editBook' ? 'Edit Book' : 'Add Book'" />

    <CreateEntityModal 
        v-if="showModal" 
        :title="`Create ${typeEntity}`"
        @hide-modal="showModal = false, focusInput()" 
        @create-entity="submit" 
        :entity = entity 
        :typeEntity="typeEntity"
    />

    <Form @submit="onSubmit" :validation-schema="validateForm" class="form-control" :initial-values="route.name == 'editBook' ? booksStore.bookDetails : undefined " ref="myForm">
        <div class="form-group image">
            <img :src="imgSrc?.toString() || $filters.imageFilter(booksStore.bookDetails.image)" v-if="imgSrc?.toString() || booksStore.bookDetails.image" />
            <div v-else class="overlay">
                <label for="image">Select a photo</label>
            </div>
            <input name="select_image" type="file" @change="onFile">
            <ErrorMessage name="image" />
        </div>
        <div class="form-group">
            <label for="title">Title <span class="mandatory">*</span> </label>
            <Field name="title" />
            <ErrorMessage name="title" />
        </div>
        <div class="form-group">
            <label for="year">Year <span class="mandatory">*</span> </label>
            <Field name="year" />
            <ErrorMessage name="year" />
        </div>
        <div class="form-group">
            <label for="quantity">Quantity <span class="mandatory">*</span> </label>
            <Field name="quantity" />
            <ErrorMessage name="quantity" />
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <Field name="price" />
            <ErrorMessage name="price" />
        </div>
        <div class="form-group">
            <Field name="publisher" type="hidden" :value="selectedPublisher" v-model="selectedPublisher" />
            <label for="price">Publisher <span class="mandatory">*</span> </label>
            <AutoComplete 
                :scroll-height="filteredPublishers.length == 1 ? '51px' :filteredPublishers.length == 2 ? '86px' : filteredPublishers.length == 3 ? '121px' : '150px'"
                name="publisher" v-model="selectedPublisher" :suggestions="filteredPublishers"  optionLabel="name" 
                loadingIcon="none" dropdown dropdown-mode="current" :virtualScrollerOptions="{ itemSize: 35 }"
                @focus="onFocusElement" @complete="searchPublishers($event)"
                @item-select="
                    filteredPublishers[0].id != 0 
                        ? 
                    focusInput()
                        : 
                    (typeEntity = 'Publisher' ,selectedPublisher = {id: 0, name: '', city: '', book_count: 0}, showModal = true, blurInput())"
            />
            <ErrorMessage name="publisher" />
        </div>
        <div class="form-group">
            <Field name="authors" type="hidden" :value="selectedAuthors" v-model="selectedAuthors" />
            <label for="authors">Authors <span class="mandatory">*</span> </label>
            <AutoComplete 
                :scroll-height="filteredAuthors.length == 1 ? '55px' :filteredAuthors.length == 2 ? '86px' : filteredAuthors.length == 3 ? '121px' : '150px'"
                name="authors" v-model="selectedAuthors" :suggestions="filteredAuthors" optionLabel="name" :multiple="true"  
                loadingIcon="none" dropdown dropdown-mode="current" :virtualScrollerOptions="{ itemSize: 35 }"
                @focus="onFocusElement" @complete="searchAuthors($event)" 
                @item-select="filteredAuthors[0].id != 0 ? focusInput() : (typeEntity = 'Author', selectedAuthors.pop(), showModal = true, blurInput())"   
            />
            <ErrorMessage name="authors" />
        </div>
        <div class="form-group"> 
            <Field name="category" type="hidden" :value="selectedCategory" v-model="selectedCategory" />
            <label for="category">Category <span class="mandatory">*</span> </label>
            <AutoComplete 
                name="category" v-model="selectedCategory" :suggestions="filteredCategories"  optionLabel="name" 
                scroll-height="150px" loadingIcon="none" dropdown dropdown-mode="current"
                @focus="onFocusElement" @complete="searchCategories($event)"
                @item-select="
                filteredCategories[0].id != 0 
                    ? 
                focusInput()
                    : 
                (typeEntity = 'Category', selectedCategory = {id: 0, name: '', description: '' , number: 0 ,book_count: 0}, showModal = true, blurInput())"
            />
            <ErrorMessage name="category" />
        </div>
        <div class="mark">
            <label for="is_marked">Mark for challenge </label>
            <Field name="is_marked" type="checkbox" :value="true" :unchecked-value="false"/>
            <ErrorMessage name="is_marked" />
        </div>
       
        <input :value="route.name == 'editBook' ? 'Save' : 'Add'" type="submit" class="btn w-100">
    </Form>
</template>

<style scoped>
.image {
    width: 120px;
    margin: 0 auto;
    height: 150px;
    margin-bottom: 20px;
}
.image input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
}
.image .overlay {
    width: 100%;
    height: 100%;
    border: 1px solid #D0D5DD;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.image img {
    border-radius: 12px;
    object-fit: cover;
    width: 100%;
    height: 100%;
}
.mark{
  display: flex;
  flex-direction: row-reverse;
  justify-content: start;
  gap: 5px;
  margin-bottom: .5rem;
}
.mark label{
    color: #4D4D4D;
}
</style>