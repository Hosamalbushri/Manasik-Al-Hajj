import { test, expect } from "../setup";

const adminCredentials = {
    email: "admin@gmail.com",
    password: "123456",
};

test("should be able to login", async ({ page }) => {
    /**
     * Login as admin.
     */
    await page.goto("admin/login");
    await page.getByPlaceholder("Email Address").click();
    await page.getByPlaceholder("Email Address").fill(adminCredentials.email);
    await page.getByPlaceholder("Password").click();
    await page.getByPlaceholder("Password").fill(adminCredentials.password);
    await page.getByRole("button", { name: "Sign In" }).click();

    await expect(page).toHaveURL(/\/admin\/dashboard/);
    await expect(page.getByRole("link", { name: "Visit website" })).toBeVisible();
});

test("should be able to logout", async ({ adminPage }) => {
    await expect(adminPage).toHaveURL(/\/admin\/dashboard/);

    const profileToggle = adminPage.getByRole("banner").getByRole("button").last();

    await expect(profileToggle).toBeVisible();
    await profileToggle.click();

    const signOutLink = adminPage.getByRole("link", { name: "Sign Out" });

    await expect(signOutLink).toBeVisible();
    await signOutLink.click();

    await expect(adminPage).toHaveURL(/\/admin\/login/);
    await expect(adminPage.locator('input[name="password"]')).toBeVisible();
});
