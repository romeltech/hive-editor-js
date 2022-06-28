<template>
  <div>
    <car-form
      :objectdata="objectData"
      :pagetitle="'edit'"
      @saved="savedResponse"
    ></car-form>
  </div>
</template>

<script>
import CarForm from "./CarForm.vue";

export default {
  components: { CarForm },
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
