export default class Role {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.urole === "admin";
    }

    isUser() {
        return this.user.urole === "user";
    }

    isAdminOrUser() {
        //return this.user.type === "user" || this.user.type === "admin";
        return true;
    }
}
