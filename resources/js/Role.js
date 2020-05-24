export default class Role {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === "admin";
    }

    isUser() {
        return this.user.type === "user";
    }

    isAdminOrUser() {
        //return this.user.type === "user" || this.user.type === "admin";
        return true;
    }
}
