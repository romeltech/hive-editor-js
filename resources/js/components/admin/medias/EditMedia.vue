<template>
  <div>
    <media-form
      :objectdata="objectData"
      :newurl="'/d/admin/media/save'"
      :pagetitle="'edit'" 
      :deleteurl="'/d/admin/media/delete/'"
      :redirectname="'medias'"
      :redirectedit="'EditMedia'"
      :redirectnew="'NewMedia'"
      :headertitle="'News & Articles'"
      :posttype="'post'"
      @saved="savedResponse"
    ></media-form>
  </div>
</template>

<script>
import MediaForm from "./MediaForm.vue";

export default {
  components: { MediaForm },
  data() {
    return {
      objectData: {},
    };
  },
  methods: {
    getData() {
      axios
        .get("/d/admin/media/get/" + this.$route.params.id)
        .then((response) => {
          this.objectData = Object.assign({}, response.data);
        })
        .catch((err) => {});
    },
    savedResponse() {
      this.getData();
    },
  },
  mounted() {
    this.getData();
  },
};
</script>