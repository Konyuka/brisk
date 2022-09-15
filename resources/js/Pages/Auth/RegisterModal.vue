<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/inertia-vue3";
import { computed, ref, watch } from "vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const currentMessage = computed(() => usePage().props.value.flash.success);


watch(currentMessage, (value) => {
  if (value == null) {
    currentMessageText.value = false;
  } else { 
    currentMessageText.value = true;
  }
});
const currentMessageText = ref(false);
watch(currentMessageText, (value) => {
  if (value == true) {
    setTimeout(() =>
    { 
      currentMessageText.value = false
    }, 3000)
  } 
});


const form = useForm({
  name: "",
  email: "",
  password: "password",
  password_confirmation: "password",
  terms: false,
  permission: 4
});


const submit = () => {
  form.post("/dashboard/user_register", {
    onFinish: () =>
    {
      form.reset("name", "email")
      currentMessageText.value = true;
    },
  });
};
</script>

<template>
  <Head title="Register" />

  <!-- <JetAuthenticationCard class="bg-[url('/img/bg.jpg')]"> -->
    <p v-if="currentMessageText" class="my-5 italic text-sm text-green-900 font-bold">{{ currentMessage }}</p>
    <JetValidationErrors class="mb-4" />


  <form @submit.prevent="submit">
    <div>
      <JetLabel for="name" value="Name" />
      <JetInput
        id="name"
        v-model="form.name"
        type="text"
        class="mt-1 block w-full"
        required
        autofocus
        autocomplete="name"
      />
    </div>

    <div class="mt-4">
      <JetLabel for="email" value="Email" />
      <JetInput
        id="email"
        v-model="form.email"
        type="email"
        class="mt-1 block w-full"
        required
      />
    </div>

    <div class="mt-4">
      <!-- <JetInput
        id="email"
        v-model="form.email"
        type="email"
        class="mt-1 block w-full"
        required
      /> -->

      <div>
        <label for="location" class="block text-sm font-medium text-gray-700"
          >Permissons</label
        >
        <select
          v-model="form.permission"
          id="location"
          name="location"
          class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
        >
          <option value="4">Sales person</option>
          <option value="3">Team Lead</option>
          <option value="2">Sales Manager</option>
        </select>
      </div>
    </div>

    <div class="mt-4">
      <JetLabel class="hidden" for="password" value="Password" />
      <JetInput
        id="password"
        v-model="form.password"
        type="password"
        class="hidden mt-1 w-full"
        required
        autocomplete="new-password"
        disabled
      />
    </div>

    <div class="mt-4">
      <JetLabel class="hidden" for="password_confirmation" value="Confirm Password" />
      <JetInput
        id="password_confirmation"
        v-model="form.password_confirmation"
        type="password"
        class="hidden mt-1 w-full"
        required
        autocomplete="new-password"
        disabled
      />
    </div>

    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
      <JetLabel for="terms">
        <div class="flex items-center">
          <JetCheckbox id="terms" v-model:checked="form.terms" name="terms" />

          <div class="ml-2">
            I agree to the
            <a
              target="_blank"
              :href="route('terms.show')"
              class="underline text-sm text-gray-600 hover:text-gray-900"
              >Terms of Service</a
            >
            and
            <a
              target="_blank"
              :href="route('policy.show')"
              class="underline text-sm text-gray-600 hover:text-gray-900"
              >Privacy Policy</a
            >
          </div>
        </div>
      </JetLabel>
    </div>

    <div class="flex items-center justify-end mt-4">
      <!-- <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 hover:text-gray-900"
        >
          Already registered?
        </Link> -->

      <JetButton
        class="ml-4 bg-light-green-900"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Register User <i class="ml-2 fa fa-person-circle-plus fa-xl"></i>
      </JetButton>
    </div>
  </form>
  <!-- </JetAuthenticationCard> -->
</template>
