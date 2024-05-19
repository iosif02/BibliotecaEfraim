<script setup lang="ts">
import type AuthorModel from '@/models/entities/AuthorModel';
import { Form, Field, ErrorMessage } from 'vee-validate';
import type { PropType } from 'vue';
import * as yup from 'yup';

const props = defineProps({
  initialValue: Object as PropType<AuthorModel>,
})

const validateForm = yup.object({
    name: yup.string().min(5).max(28).required(),
});

const emit = defineEmits(['submit'])
var onSubmit = (author: any) => {
    emit.call(this, 'submit', author);
}
</script>

<template>
  <Form @submit="onSubmit" :validation-schema="validateForm" :initial-values="props.initialValue ?? undefined" class="form-control">
    <div class="form-group">
      <label for="name">Name <span class="mandatory">*</span> </label>
      <Field name="name" />
      <ErrorMessage name="name" />
    </div>
    <input :value="!initialValue ? 'Add' : 'Save'" type="submit" class="btn w-100">
  </Form>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>