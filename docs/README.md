# Документація проєкту

У цій папці підготовлено матеріали для практичної роботи DOCS:

- `SSD.md` - System Specification Document;
- `BRD.md` - Business Requirements Document;
- `STUDENT_DOCS.md` - документ у стилі дипломного проєкту для практичної STUDENT_DOCS;
- `openapi.yaml` - Swagger/OpenAPI опис двох endpoint-ів;
- `postman_collection.json` - Postman collection для тих самих endpoint-ів.

Базовий URL для локального запуску: `http://localhost/site`.

Описані endpoint-и:

- `GET /cart/add?product_id={id}&quantity={qty}`;
- `GET /profile/check-email?email={email}`.

Для перевірки `/cart/add` потрібно спочатку авторизуватися в застосунку, щоб браузер або Postman мав активну PHP session cookie.
