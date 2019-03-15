
const messages = {
	interface: {
		tagline: 'Shopping for everyday life',
		demographic: {
			traffic_count: 'Traffic Count',
			population: 'Population',
			demographics_2017: '2017 Demographics',
			avg_household_income: 'Avg. Household Income',
			total_households: 'Total Households',
			aadt: 'AADT',
		}
	},
	buttons: {
		ok: 'ok',
		cancel: 'Cancel',
		save: 'Save',
		saving: 'Saving...',
		request: 'Save and Request Approval',
		requesting: 'Requesting...',
		add_site_plan: 'Add Site Plan',
		add_an_image: 'Add an image',
		add_more_site_plans: 'Add More Site Plans'
	},
	labels: {
		errors_before_save: 'Please correct the following errors before saving'
	},
	errors: {
		failed_to_load_image: 'Failed to load image',
		plans_required: 'One or more site plans are required',
		description_required: 'Marketing description is required',
		images_required: 'You should specify all required images',
		something_went_wrong: 'Something went wrong!',
		domexception: 'DOM Exception. It looks like you are trying to edit an image from another domain.',
		exception: 'Something went wrong: ',
	}
};

const messages_fr = {
	interface: {
		tagline: 'Vos achats au quotidien',
		demographic: {
			traffic_count: 'Recensement de la circulation',
			population: 'Population',
			demographics_2017: 'Démographiques de 2017',
			avg_household_income: 'Revenu moyen du ménage',
			total_households: 'Total des ménages',
			aadt: 'TMJA',
		}
	},
	buttons: {
		ok: 'ok',
		cancel: 'Cancel',
		save: 'Save',
		saving: 'Saving...',
		request: 'Save and Request Approval',
		requesting: 'Requesting...',
		add_site_plan: 'Add Site Plan',
		add_an_image: 'Add an image',
		add_more_site_plans: 'Add More Site Plans'
	},
	labels: {
		errors_before_save: 'Please correct the following errors before saving'
	},
	errors: {
		failed_to_load_image: 'Failed to load image',
		plans_required: 'One or more site plans are required',
		description_required: 'Marketing description is required',
		images_required: 'You should specify all required images',
		something_went_wrong: 'Something went wrong!',
		domexception: 'DOM Exception. It looks like you are trying to edit an image from another domain.',
		exception: 'Something went wrong: ',
	}
};

export const getLang = (locale) => {
	locale = (locale || '').toLowerCase();
	if (locale === 'fr' || locale === 'fr_ca' || locale === 'fr-ca') {
		return messages_fr;
	}

	return messages;
};
