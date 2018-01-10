const ApiContext = require('../../modules/ApiContext');
const $ = require('cafy').default;

/** @param {ApiContext} apiContext */
exports.post = async (apiContext) => {
	await apiContext.proceed({
		body: {
			userId: { cafy: $().string() }
		},
		headers: ['x-ice-auth-key'],
		permissions: ['iceAuthHost']
	});
	if (apiContext.responsed) return;

	const iceAuthKey = apiContext.headers['x-ice-auth-key'];
	const userId = apiContext.body.userId;

	const { verifyIceAuthKey, splitIceAuthKey, setTargetUserId } = apiContext.authorizeRequestsService;

	if (!await verifyIceAuthKey(iceAuthKey)) {
		return apiContext.response(400, 'x-ice-auth-key header is invalid');
	}

	if ((await apiContext.db.users.findByIdAsync(userId)) == null) { //TODO: move to document models
		return apiContext.response(400, 'userId is invalid');
	}

	const authorizeRequestId = splitIceAuthKey(iceAuthKey).authorizeRequestId;
	const authorizeRequest = await apiContext.repository.findById('authorizeRequests', authorizeRequestId);
	await setTargetUserId(authorizeRequest, userId);

	apiContext.response(200, { targetUserId: authorizeRequest.targetUserId });
};
