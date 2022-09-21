<template>
  <p class="paragraph" v-html="content.text"></p>
</template>

<script>
export default {
  props: {
    content: {
      type: Object,
      default: {},
    },
  },
};
</script>
