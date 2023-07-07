<template>
	<v-card class="mx-auto d-inline-flex flex-column" width="400">
		<v-img v-if="videoObject.kind == 'youtube#channel'" class="align-end text-white" max-height="200" :src="videoObject.thumbnails.medium.url" cover />
		<iframe v-else max-height="200" class="align-end text-white" :src="'https://www.youtube.com/embed/'+videoObject.videoId" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		<v-card-text class="pa-2 ma-0">
			<div><b>{{ videoObject.title }}</b></div>
		</v-card-text>
		<v-card-actions class="pa-2 ma-0">
			<div><v-icon class="ma-1" large color="teal darken-2">mdi-feather</v-icon> {{ videoObject.channelTitle }}</div>
			<div><v-icon class="ma-1" large color="teal darken-2">mdi-calendar</v-icon> {{ (new Date(videoObject.videoPublishedAt)).toDateString()}}</div>
		</v-card-actions>
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