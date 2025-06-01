window.onload = function() {
    // Begin Swagger UI call region
    const ui = SwaggerUIBundle({
        url: "/api-docs",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "BaseLayout",
        persistAuthorization: true,
        oauth2RedirectUrl: window.location.origin + '/api/oauth2-callback',
        configs: {
            oauth2: {
                usePkceWithAuthorizationCodeGrant: false
            }
        },
        onComplete: function() {
            // Get the token from localStorage if it exists
            const token = localStorage.getItem('swagger_token');
            if (token) {
                // Set the token in the authorization header
                ui.preauthorizeApiKey('passport', 'Bearer ' + token);
            }
        }
    });
    // End Swagger UI call region

    window.ui = ui;
};
