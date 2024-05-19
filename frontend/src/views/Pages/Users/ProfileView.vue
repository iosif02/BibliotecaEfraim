<script setup lang="ts">
import { ref } from 'vue';
import { useUsersStore } from '@/stores/user-store';
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import type RoleModel from '@/models/user/RoleModel';
import moment from 'moment';

const store = useUsersStore()
if(!store.profile.id)
  store.fetchProfile();
const profileId = ref<any>(null);
  
if(!store.roles.length)
  store.fetchRoles();

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

const validateForm = yup.object({
  email: yup.string().required().email(),
  first_name: yup.string().required(),
  last_name: yup.string().required(),
  address: yup.string().nullable(),
  phone: yup.string().required().min(10),
  occupation: yup.string().nullable(),
  birth_date: yup.date().required(),

  identity_number: yup.string().when(value =>(
    value ? yup.string().min(13).max(13) : yup.string().nullable() 
  )),

  password: yup.string().when(value =>(
    value ? yup.string().min(8) : yup.string().nullable() 
  )),
	password_confirmation: yup.string().nullable().oneOf([yup.ref('password')], 'Passwords do not match')
});

const backgroundColor = [
    'background-color: #001219;',
    'background-color: #005F73;',
    'background-color: #0A9396;',
    'background-color: #94D2BD;',
    'background-color: #E9D8A6;',
    'background-color: #EE9B00;',
    'background-color: #CA6702;',
    'background-color: #BB3E03;',
    'background-color: #AE2012;',
    'background-color: #9B2226;',
]

function getRandomColor() {
    const randomNumber = Math.floor(Math.random() * 10)
    return backgroundColor[randomNumber]
}

function getNameIntial(first_initial: String, second_initial: String) {
    if(first_initial == second_initial) return first_initial;
    return first_initial + '' + second_initial;
}

const backgroundColorStyle = getRandomColor();

var onSubmit = (user: any) => {
  const identity_number = user.identity_number == null || user.identity_number == '-' ? '' : user.identity_number;
  let formData = new FormData()
  profileId.value = store.profile.id
  formData.append('email', user?.email)
  formData.append('identity_number', identity_number);
  formData.append('first_name', user?.first_name)
  formData.append('last_name', user?.last_name)
  user.password && formData.append('password', user.password)
  user.password_confirmation && formData.append('password_confirmation', user.password_confirmation)
  formData.append('address', user?.address || '')
  formData.append('phone', user?.phone)
  formData.append('occupation', user?.occupation || '')
  formData.append('birth_date', user?.birth_date)
  formData.append('userId', profileId.value)

  user.roles.map((x: any) => {
      formData.append('roles[]', x.id)
  });
  
  if(imgObj.value) formData.append('imageFile', imgObj.value)

  store.updateUser(formData)
  .then(result => {
    if(result){
      store.fetchProfile();
      isEdit.value = false;
    }
  });
}

const filteredRoles = ref<Array<RoleModel>>([]);
const selectedRoles = ref<Array<RoleModel>>([]);

var searchRoles = (event: any) => {
  filteredRoles.value = store.roles.filter(x => x.name.toLowerCase().includes(event.query.toLowerCase())).filter(x => !selectedRoles.value.some(x2 => x.id === x2.id));;
}

let isEdit = ref<boolean>(false);
var toggleEdit = () => {
  if(!isEdit.value)
    store.fetchProfile();
  isEdit.value = !isEdit.value
}

let formatDate = (date: any) => {
  if (date) {
      return moment(date, "YYYY-MM-DD h:mm:ss").format("DD MMMM Y");
  }
  return date;
}

let focusedElement: any = ref(null)

var blurInput = (e: any) => {
    if(focusedElement) focusedElement?.target?.focus();
    focusedElement.value = null;
}

function onFocusElement(e: any) {
    focusedElement = e;
}

const originalValue = store.user.user_details.identity_number;
const transformedValue = originalValue !== 0 ? String(originalValue) : null;

const identity_number = {
  identity_number: transformedValue === '-' ? null : transformedValue
};

</script>

<template>
  <Loading v-if="store.isLoading" />

  <GoBack goBackText="Profile"/>

  <Form v-if="isEdit" @submit="onSubmit" :validation-schema="validateForm" class="form-control" :initial-values="{ ...store.profile, ...store.profile.user_details, ...identity_number }" ref="myForm">
    <div class="profile-image-container">
      <div v-if="imgSrc?.toString() || store.profile.user_details?.photo_url" >
        <img :src="imgSrc?.toString() || $filters.imageFilter(store.profile.user_details?.photo_url)" />
      </div>
      <div v-else class="profile-image" :style="backgroundColorStyle">
        <p> {{ getNameIntial(store.profile?.first_name[0], store.profile?.last_name[0]) }} </p>
      </div>
      <input name="select_image" type="file" @change="onFile">
      <button @click="toggleEdit" class="btn-edit" style="background-color: rgba(250, 250, 250, 0.3)">Cancel</button>
      <ErrorMessage name="image" />
    </div>
    <div class="profile-details">
      <div class="form-group">
        <label for="first_name">First name <span class="mandatory">*</span> </label>
        <Field name="first_name" />
        <ErrorMessage name="first_name" />
      </div>
      <div class="form-group">
        <label for="last_name">Last name <span class="mandatory">*</span> </label>
        <Field name="last_name" />
        <ErrorMessage name="last_name" />
      </div>


      <div class="form-group">
        <label for="password">Password</label>
        <Field name="password" type="password" />
        <ErrorMessage name="password" />
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <Field name="password_confirmation" type="password" />
        <ErrorMessage name="password_confirmation" />
      </div>


      <div class="form-group">
        <label for="identity_number">Identity number</label>
        <Field name="identity_number" type="number" />
        <ErrorMessage name="identity_number" />
      </div>
      <div class="form-group">
        <label for="phone">Phone  <span class="mandatory">*</span> </label>
        <Field name="phone" type="number" />
        <ErrorMessage name="phone" />
      </div>
      <div class="form-group">
        <label for="email">Email  <span class="mandatory">*</span> </label>
        <Field name="email" />
        <ErrorMessage name="email" />
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <Field name="address" />
        <ErrorMessage name="address" />
      </div>
      <div class="form-group">
        <label for="occupation">Occupation</label>
        <Field name="occupation" />
        <ErrorMessage name="occupation" />
      </div>
      <div class="form-group">
        <label for="birth_date">Birth date  <span class="mandatory">*</span> </label>
        <Field name="birth_date" type="date" min="1950-01-01" max="2050-12-31" />
        <ErrorMessage name="birth_date" />
      </div>
      <div class="form-group">
        <Field name="roles" type="hidden" :value="selectedRoles" v-model="selectedRoles" />
        <label for="authors">Roles <span class="mandatory">*</span> </label>
        <AutoComplete 
          name="roles" v-model="selectedRoles" :suggestions="filteredRoles" @complete="searchRoles($event)" optionLabel="name" :dropdown="true" :multiple="true" 
          @focus="onFocusElement" @item-select="blurInput"
        />
        <ErrorMessage name="roles" />
      </div>
    </div>
    <input value="Save" type="submit" class="btn w-100">
  </Form>

  <div v-else>
    <div class="profile-image-container">
      <div v-if="imgSrc?.toString() || store.profile.user_details?.photo_url" style="position: relative;">
        <img :src="imgSrc?.toString() || $filters.imageFilter(store.profile.user_details?.photo_url)" />
      </div>
      <div v-else class="profile-image" :style="backgroundColorStyle">
        <p> {{ getNameIntial(store.profile?.first_name[0], store.profile?.last_name[0]) }} </p>
      </div>
      <button @click="toggleEdit" class="btn-edit" style="background-color: rgba(250, 250, 250, 0.3)">Edit</button>
    </div>

    <div class="profile-details">
      <div class="row" >
        <p>Email:</p>
        <p>{{ store.profile?.email }}</p>
      </div>
      <div class="row">
        <p>Identity Number:</p>
        <p>{{ store.profile.user_details?.identity_number }}</p>
      </div>
      <div class="row">
        <p>First Name:</p>
        <p>{{ store.profile?.first_name }}</p>
      </div>
      <div class="row">
        <p>Last Name:</p>
        <p>{{ store.profile?.last_name }}</p>
      </div>
      <div class="row">
        <p>Address:</p>
        <p>{{ store.profile.user_details?.address }}</p>
      </div>
      <div class="row">
        <p>Phone:</p>
        <p>{{ store.profile.user_details?.phone }}</p>
      </div>
      <div class="row">
        <p>Occupation:</p>
        <p>{{ store.profile.user_details?.occupation }}</p>
      </div>
      <div class="row">
        <p>Birth Date:</p>
        <p>{{ formatDate(store.profile.user_details?.birth_date) }}</p>
      </div>
    </div>
  </div>

</template>

<style scoped>
.profile-image-container {
  height: 150px;
  width: 100%;
  margin: 0 auto;
  margin-bottom: 20px;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: #76CECB;
  border-radius: 5px;
}
.profile-image-container input {
  top: 11px;
  position: absolute;
  opacity: 0;
  width: 100px;
  height: 100px;
}

.profile-image-container img{
  height: 100px;
  width: 100px;
  object-fit: cover;
  border-radius: 50px;
}
.profile-image {
  min-width: 100px;
  width: 100px;
  height: 100px;
  border-radius: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.profile-image p {
  color: #fff;
  font-size: 3rem;
  font-weight: 600;
}
.profile-details {
  width: 100%;
  padding: 10px 20px;
}
.profile-details .row {
  padding: 5px 10px;
  margin: 10px 0px;
  border-left: 2px solid #76CECB;

  white-space: normal;
  overflow: hidden;
  text-overflow: ellipsis;
}
.profile-details .row p:last-child{
  font-size: 16px;
  padding: 3px 15px;
  color: #4D4D4D;
}
.profile-details .row p:first-child{
  font-size: 14px;
  font-weight: 100;
  color: #667085;
}
.btn-edit{
  width: 100px;
  margin: 0px;
  margin-top: 5px;
  padding: 0px;
}
</style>