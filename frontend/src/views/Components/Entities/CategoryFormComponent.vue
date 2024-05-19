<script setup lang="ts">
import type CategoryModel from '@/models/entities/CategoryModel';
import { Form, Field, ErrorMessage } from 'vee-validate';
import type { PropType } from 'vue';
import * as yup from 'yup';

const props = defineProps({
  initialValue: Object as PropType<CategoryModel>,
})

const validateForm = yup.object({
  name: yup.string().required(),
  description: yup.string().nullable(),
  number: yup.number().required(),
});

const emit = defineEmits(['submit'])
var onSubmit = (category: any) => {
  emit.call(this, 'submit', category);
}
</script>

<template>
  <Form @submit="onSubmit" :validation-schema="validateForm" :initial-values="props.initialValue ?? undefined" class="form-control">
    <div class="form-group">
      <label for="name">Name <span class="mandatory">*</span> </label>
      <Field name="name" />
      <ErrorMessage name="name" />
    </div>
    <div class="form-group">
      <label for="description">Description</label>
      <Field name="description" />
      <ErrorMessage name="description" />
    </div>
    <div class="form-group">
      <label for="number">Number <span class="mandatory">*</span> </label>
      <Field name="number" type="number" />
      <ErrorMessage name="number" />
    </div>
    <input :value="!initialValue ? 'Add' : 'Save'" type="submit" class="btn w-100">
  </Form>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>