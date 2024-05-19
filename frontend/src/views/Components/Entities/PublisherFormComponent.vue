<script setup lang="ts">
import type PublisherModel from '@/models/entities/PublisherModel';
import { Form, Field, ErrorMessage } from 'vee-validate';
import type { PropType } from 'vue';
import * as yup from 'yup';

const props = defineProps({
  initialValue: Object as PropType<PublisherModel>,
})

const validateForm = yup.object({
  name: yup.string().required(),
  city: yup.string().nullable(),
});

const emit = defineEmits(['submit'])
var onSubmit = (publisher: any) => {
  emit.call(this, 'submit', publisher);
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
      <label for="city">City</label>
      <Field name="city" />
      <ErrorMessage name="city" />
    </div>
    <input :value="!initialValue ? 'Add' : 'Save'" type="submit" class="btn w-100">
  </Form>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>