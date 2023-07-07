<template>
	<v-card elevation="10" class="pa-5" @click="$emit('getChannelVideo',videoObject)">
		<div class="d-inline-flex justify-space-between">
			<v-avatar size="200" rounded="1">
				<v-img cover :src="videoObject.thumbnails.medium.url" />
			</v-avatar>
			<div>
				<v-card-title class="text-h5 py-0"> {{ videoObject.title }} </v-card-title>
				<v-card-text class="pt-5">
					{{ videoObject.description }}
					<div>
						<v-chip class="ma-2" close color="teal" label text-color="white">
							<v-icon left class="mr-1"> mdi-eye </v-icon> {{ numFixFormat(videoObject.statistics.viewCount) }}
						</v-chip>
						<v-chip class="ma-2" close color="teal" label text-color="white">
							<v-icon left class="mr-1"> mdi-video </v-icon> {{ numFixFormat(videoObject.statistics.videoCount) }}
						</v-chip>
						<v-chip class="ma-2" close color="teal" label text-color="white">
							<v-icon left class="mr-1"> mdi-account-group </v-icon> {{ numFixFormat(videoObject.statistics.subscriberCount) }}
						</v-chip>
						<v-chip class="ma-2" close color="teal" label text-color="white">
							<v-icon left class="mr-1"> mdi-calendar-outline </v-icon> {{ (new Date(videoObject.publishedAt)).toDateString()}}
						</v-chip>
					</div>
			</v-card-text>
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
					channelId:'',
					title:'',
					country:'',
					customUrl:'',
					description:'',
					publishedAt:'',
					contentDetails:'',
					statistics:'',
					thumbnails:''
				}
			},
		},
		methods:{
			numFixFormat(value){
				const formatter = new Intl.NumberFormat();
				return formatter.format(value);
			}
		}
	}
</script>