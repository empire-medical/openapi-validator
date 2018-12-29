##what is this? 

Validate data against openapi v3 spec [https://github.com/OAI/OpenAPI-Specification]

##Requirements

Your openapi spec has to be valid, You can use [https://github.com/wework/speccy] to check Your schema first

This library assumes that each operation has operationId

##Examples

Examples in tests

##Other libraries

1. Dredd [https://github.com/apiaryio/dredd] - currently supports only swagger/openapi v2, support for v3 is not yet there
2. [https://github.com/WakeOnWeb/swagger] - support for v2 only 

##Features

1. Supports discriminator
2. Supports nullable
3. Resolves dependecies (schema and response components)

##TODO

1. Support all openapi formats
2. Support for not keyword

##How this works?

Transform openapi spec into json schema and then uses justinrainbow/json-schema to validate it