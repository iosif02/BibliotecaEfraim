<script setup lang="ts">
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { useUsersStore } from '@/stores/user-store';
import router from '@/router';
import { ref } from 'vue';
import type RoleModel from '@/models/user/RoleModel';

const store = useUsersStore();
if(!store.roles.length) {
    store.fetchRoles();
}

const validateForm = yup.object({
  email: yup.string().required().email(),
  password: yup.string().required().min(8),
  password_confirmation: yup.string().required().oneOf([yup.ref('password')], 'Passwords do not match'),
  first_name: yup.string().required(),
  last_name: yup.string().required(),
  address: yup.string().nullable(),
  phone: yup.string().required().min(10),
  occupation: yup.string().nullable(),
  birth_date: yup.date().required(),

  identity_number: yup.string().when(value =>(
    value ? yup.string().min(13).max(13) : yup.string().nullable() 
  )),
});

var onSubmit = (user: any) => {
  user.roles = user.roles.map((x: RoleModel) => x.id);
  store.createUser(user)
  .then(result => {
    if(result)
      router.back();
  });
}

const filteredRoles = ref<Array<RoleModel>>([]);
const selectedRoles = ref<Array<RoleModel>>([]);

var searchRoles = (event: any) => {
  filteredRoles.value = store.roles.filter(x => x.name.toLowerCase().includes(event.query.toLowerCase())).filter(x => !selectedRoles.value.some(x2 => x.id === x2.id));
}

let focusedElement: any = ref(null)

var blurInput = (e: any) => {
    if(focusedElement) focusedElement?.target?.focus();
    focusedElement.value = null;
}

function onFocusElement(e: any) {
    focusedElement = e;
}

</script>

<template>
  <Loading v-if="store.isLoading" />

	<div>
    <GoBack goBackText="Add User"/>
	</div>

  <Form @submit="onSubmit" :validation-schema="validateForm" class="form-control">
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
      <label for="password">Password <span class="mandatory">*</span> </label>
      <Field name="password" type="password"/>
      <ErrorMessage name="password" />
    </div>
    <div class="form-group">
      <label for="password">Confirm Password <span class="mandatory">*</span> </label>
			<Field name="password_confirmation" type="password" />
			<ErrorMessage name="password_confirmation" />
		</div>
    <div class="form-group">
      <label for="identity_number">Identity number</label>
      <Field name="identity_number" type="number" />
      <ErrorMessage name="identity_number" />
    </div>
    <div class="form-group">
      <label for="phone">Phone <span class="mandatory">*</span> </label>
      <Field name="phone" type="number" maxlength="2"/>
      <ErrorMessage name="phone" />
    </div>
    <div class="form-group">
      <label for="email">Email <span class="mandatory">*</span> </label>
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
      <label for="birth_date">Birth date <span class="mandatory">*</span> </label>
      <Field name="birth_date" type="date" />
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
    <input value="Add" type="submit" class="btn w-100">
  </Form>
</template>

<style scoped>
.checkbox{
  display: flex;
  flex-direction: row;
}
.checkbox label{
  margin-right: 10px;
}
</style>