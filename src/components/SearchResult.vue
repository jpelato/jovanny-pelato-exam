<template>
	<v-card elevation="0" class="" style="background-color: transparent;" @click="$emit('getChannelVideo', videoObject.channelId)">
		<div class="d-inline-flex flex-no-wrap justify-space-between">
			<v-avatar size="150" rounded="1">
				<v-img class="align-end text-white" :src="videoObject.thumbnails.medium.url" cover />
			</v-avatar>
			<div>
				<v-card-title> {{ videoObject.title }}</v-card-title>
				<v-card-subtitle> {{ videoObject.channelTitle }}</v-card-subtitle>
				<v-card-text class="text-justify">{{ videoObject.description }}</v-card-text>
			</div>
		</div>
	</v-card>
</template>
<script>
	export default {
		emits: ['getChannelVideo'],
		props:{
			videoObject:{
				type: Object,
				default: {
					kind:'',
					videoId:'',
					channelId:'',
					title:'',
					channelTitle:'',
					description:'',
					thumbnails:'',
					videoPublishedAt:''
				}
			},
		},
	}
</script>