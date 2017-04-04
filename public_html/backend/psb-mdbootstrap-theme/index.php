<?php

preg_match(
    '/(<\?xml.*)(<w:p .*>\${'.$blockName.'}<\/w:.*?p>)(.*)(<w:p .*>\${\/'.$blockName.'}<\/w:.*?p>)/is',
    $this->tempDocumentMainPart,
    $matches
);