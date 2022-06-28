<template>
  <div>
    <notification-form
      :objectdata="objectData"
      :pagetitle="'edit'"
      @saved="savedResponse"
    ></notification-form>
  </div>
</template>

<script>
import NotificationForm from "./NotificationForm.vue";

export default {
  components: { NotificationForm },
  data() {
    return {
      objectData: {},
    };
  },
  methods: {
    getUser() {
      axios
        .get("/d/user/get/" + this.$route.params.id)
        .then((response) => {
          this.objectData = Object.assign({}, response.data);
        })
        .catch((err) => {});
    },
    savedResponse() {
      this.getUser();
    },
  },
  mounted() {
    this.getUser();
  },
};
</script>

<style>
</style>
