<script setup lang="ts">
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { useEntitiesStore } from '@/stores/entities-store';
import router from '@/router';

const store = useEntitiesStore();

const validateForm = yup.object({
    name: yup.string().required(),
    city: yup.string().required(),
});

var onSubmit = (publisher: any) => {
  store.createPublisher(publisher)
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
    <GoBack goBackText="Add Publisher"/>
	</div>

  <Form @submit="onSubmit" :validation-schema="validateForm" class="form-control">
    <div class="form-group">
      <label for="name">Name</label>
      <Field name="name" />
      <ErrorMessage name="name" />
    </div>
    <div class="form-group">
      <label for="city">City</label>
      <Field name="city" />
      <ErrorMessage name="city" />
    </div>
    <input value="Add" type="submit" class="btn w-100">
  </Form>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>