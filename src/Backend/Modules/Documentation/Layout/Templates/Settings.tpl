{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblModuleSettings|ucfirst}: {$lblDocumentation}</h2>
</div>

{form:settings}
    <div class="box horizontal documentationSettings">
        <div class="heading">
            <h3>{$lblGithub|ucfirst}</h3>
        </div>
        <div class="options">
            <h4>{$lblRepository|ucfirst}</h4>
            <p></p>
            <p>
                {$msgRepositoryHelp|ucfirst}
            </p>
            <p>
                <label for="username">{$lblOrganization|ucfirst}</label>
                {$txtOrganization} {$txtOrganizationError}
            </p>
            <p>
                <label for="repository">{$lblRepository|ucfirst}</label>
                {$txtRepository} {$txtRepositoryError}
            </p>
        </div>
        <div class="options">
            <h4>{$lblAuthentication|ucfirst}</h4>
            <p></p>
            <p>
                <label for="authToken">{$lblAuthToken|ucfirst}</label>
                {$txtAuthToken} {$txtAuthTokenError}
                <span class="helpTxt">{$msgTokenHelp}</span>
            </p>
        </div>
    </div>

    <div class="fullwidthOptions">
        <div class="buttonHolderRight">
            <input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
        </div>
    </div>
{/form:settings}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
