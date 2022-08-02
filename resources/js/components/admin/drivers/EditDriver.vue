<template>
  <div>
    <driver-form
      :objectdata="objectData"
      :pagetitle="'edit'"
      @saved="savedResponse"
    ></driver-form>
  </div>
</template>

<script>
import DriverForm from "./DriverForm.vue";

export default {
  components: { DriverForm },
  data() {
    return {
      objectData: {},
    };
  },
  methods: {
    getUser() {
      axios
        .get("/d/driver/get/" + this.$route.params.id)
        .then((response) => {
          console.log(response.data);
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
