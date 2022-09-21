<template>
  <v-img :src="content.url"></v-img>
</template>

<script>
export default {
  props: {
    content: {
      type: Object,
      default: {},
    },
  },
  created() {
    console.log("props", this.content);
  },
};
</script>
