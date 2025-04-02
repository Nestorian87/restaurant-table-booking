window.AdminAuth = {
    checkTokenOrRedirect: function (loginRoute) {
        const token = localStorage.getItem('admin_token');
        if (!token) {
            window.location.href = loginRoute;
        }
    },

    handleAuthError: function (response, loginRoute) {
        if (response.status === 401 || response.status === 403) {
            localStorage.removeItem('admin_token');
            window.location.href = loginRoute;
        }
    },

    getToken: function () {
        return localStorage.getItem('admin_token');
    }
};
