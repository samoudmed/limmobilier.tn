<?php
// src/App/Entity/traffic.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TrafficRepository")
 * @ORM\Table(name="traffic")
 */
class Traffic {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $argv;
            
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $argc;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $gatewayInterface;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverAddr;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverName;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverSoftware;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverProtocol;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $requestMethod;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $requestTime;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $requestTime_float;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $queryString;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $documentRoot;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpAccept;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpAcceptCharset;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpAcceptEncoding;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpAcceptLanguage;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpConnection;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpHost;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpReferer;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $httpUserAgent;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $https;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $remoteAddr;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $hostName;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $remoteHost;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $remotePort;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $remoteUser;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $redirectRemoteUser;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $scriptFilename;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverAdmin;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverPort;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $serverSignature;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pathTranslated;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $scriptName;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $requestUri;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phpAuthDigest;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phpAuthUser;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phpAuthPw;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $authType;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pathInfo;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $origPathInfo;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $connectedAt;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set argv.
     *
     * @param string $argv
     *
     * @return Traffic
     */
    public function setArgv($argv)
    {
        $this->argv = $argv;

        return $this;
    }

    /**
     * Get argv.
     *
     * @return string
     */
    public function getArgv()
    {
        return $this->argv;
    }

    /**
     * Set argc.
     *
     * @param string $argc
     *
     * @return Traffic
     */
    public function setArgc($argc)
    {
        $this->argc = $argc;

        return $this;
    }

    /**
     * Get argc.
     *
     * @return string
     */
    public function getArgc()
    {
        return $this->argc;
    }

    /**
     * Set gatewayInterface.
     *
     * @param string $gatewayInterface
     *
     * @return Traffic
     */
    public function setGatewayInterface($gatewayInterface)
    {
        $this->gatewayInterface = $gatewayInterface;

        return $this;
    }

    /**
     * Get gatewayInterface.
     *
     * @return string
     */
    public function getGatewayInterface()
    {
        return $this->gatewayInterface;
    }

    /**
     * Set serverAddr.
     *
     * @param string $serverAddr
     *
     * @return Traffic
     */
    public function setServerAddr($serverAddr)
    {
        $this->serverAddr = $serverAddr;

        return $this;
    }

    /**
     * Get serverAddr.
     *
     * @return string
     */
    public function getServerAddr()
    {
        return $this->serverAddr;
    }

    /**
     * Set serverName.
     *
     * @param string $serverName
     *
     * @return Traffic
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;

        return $this;
    }

    /**
     * Get serverName.
     *
     * @return string
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * Set serverSoftware.
     *
     * @param string $serverSoftware
     *
     * @return Traffic
     */
    public function setServerSoftware($serverSoftware)
    {
        $this->serverSoftware = $serverSoftware;

        return $this;
    }

    /**
     * Get serverSoftware.
     *
     * @return string
     */
    public function getServerSoftware()
    {
        return $this->serverSoftware;
    }

    /**
     * Set serverProtocol.
     *
     * @param string $serverProtocol
     *
     * @return Traffic
     */
    public function setServerProtocol($serverProtocol)
    {
        $this->serverProtocol = $serverProtocol;

        return $this;
    }

    /**
     * Get serverProtocol.
     *
     * @return string
     */
    public function getServerProtocol()
    {
        return $this->serverProtocol;
    }

    /**
     * Set requestMethod.
     *
     * @param string $requestMethod
     *
     * @return Traffic
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }

    /**
     * Get requestMethod.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Set requestTime.
     *
     * @param string $requestTime
     *
     * @return Traffic
     */
    public function setRequestTime($requestTime)
    {
        $this->requestTime = $requestTime;

        return $this;
    }

    /**
     * Get requestTime.
     *
     * @return string
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * Set requestTimeFloat.
     *
     * @param string $requestTimeFloat
     *
     * @return Traffic
     */
    public function setRequestTimeFloat($requestTimeFloat)
    {
        $this->requestTime_float = $requestTimeFloat;

        return $this;
    }

    /**
     * Get requestTimeFloat.
     *
     * @return string
     */
    public function getRequestTimeFloat()
    {
        return $this->requestTime_float;
    }

    /**
     * Set queryString.
     *
     * @param string $queryString
     *
     * @return Traffic
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * Get queryString.
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Set documentRoot.
     *
     * @param string $documentRoot
     *
     * @return Traffic
     */
    public function setDocumentRoot($documentRoot)
    {
        $this->documentRoot = $documentRoot;

        return $this;
    }

    /**
     * Get documentRoot.
     *
     * @return string
     */
    public function getDocumentRoot()
    {
        return $this->documentRoot;
    }

    /**
     * Set httpAccept.
     *
     * @param string $httpAccept
     *
     * @return Traffic
     */
    public function setHttpAccept($httpAccept)
    {
        $this->httpAccept = $httpAccept;

        return $this;
    }

    /**
     * Get httpAccept.
     *
     * @return string
     */
    public function getHttpAccept()
    {
        return $this->httpAccept;
    }

    /**
     * Set httpAcceptCharset.
     *
     * @param string $httpAcceptCharset
     *
     * @return Traffic
     */
    public function setHttpAcceptCharset($httpAcceptCharset)
    {
        $this->httpAcceptCharset = $httpAcceptCharset;

        return $this;
    }

    /**
     * Get httpAcceptCharset.
     *
     * @return string
     */
    public function getHttpAcceptCharset()
    {
        return $this->httpAcceptCharset;
    }

    /**
     * Set httpAcceptEncoding.
     *
     * @param string $httpAcceptEncoding
     *
     * @return Traffic
     */
    public function setHttpAcceptEncoding($httpAcceptEncoding)
    {
        $this->httpAcceptEncoding = $httpAcceptEncoding;

        return $this;
    }

    /**
     * Get httpAcceptEncoding.
     *
     * @return string
     */
    public function getHttpAcceptEncoding()
    {
        return $this->httpAcceptEncoding;
    }

    /**
     * Set httpAcceptLanguage.
     *
     * @param string $httpAcceptLanguage
     *
     * @return Traffic
     */
    public function setHttpAcceptLanguage($httpAcceptLanguage)
    {
        $this->httpAcceptLanguage = $httpAcceptLanguage;

        return $this;
    }

    /**
     * Get httpAcceptLanguage.
     *
     * @return string
     */
    public function getHttpAcceptLanguage()
    {
        return $this->httpAcceptLanguage;
    }

    /**
     * Set httpConnection.
     *
     * @param string $httpConnection
     *
     * @return Traffic
     */
    public function setHttpConnection($httpConnection)
    {
        $this->httpConnection = $httpConnection;

        return $this;
    }

    /**
     * Get httpConnection.
     *
     * @return string
     */
    public function getHttpConnection()
    {
        return $this->httpConnection;
    }

    /**
     * Set httpHost.
     *
     * @param string $httpHost
     *
     * @return Traffic
     */
    public function setHttpHost($httpHost)
    {
        $this->httpHost = $httpHost;

        return $this;
    }

    /**
     * Get httpHost.
     *
     * @return string
     */
    public function getHttpHost()
    {
        return $this->httpHost;
    }

    /**
     * Set httpReferer.
     *
     * @param string $httpReferer
     *
     * @return Traffic
     */
    public function setHttpReferer($httpReferer)
    {
        $this->httpReferer = $httpReferer;

        return $this;
    }

    /**
     * Get httpReferer.
     *
     * @return string
     */
    public function getHttpReferer()
    {
        return $this->httpReferer;
    }

    /**
     * Set httpUserAgent.
     *
     * @param string $httpUserAgent
     *
     * @return Traffic
     */
    public function setHttpUserAgent($httpUserAgent)
    {
        $this->httpUserAgent = $httpUserAgent;

        return $this;
    }

    /**
     * Get httpUserAgent.
     *
     * @return string
     */
    public function getHttpUserAgent()
    {
        return $this->httpUserAgent;
    }

    /**
     * Set https.
     *
     * @param string $https
     *
     * @return Traffic
     */
    public function setHttps($https)
    {
        $this->https = $https;

        return $this;
    }

    /**
     * Get https.
     *
     * @return string
     */
    public function getHttps()
    {
        return $this->https;
    }

    /**
     * Set remoteAddr.
     *
     * @param string $remoteAddr
     *
     * @return Traffic
     */
    public function setRemoteAddr($remoteAddr)
    {
        $this->remoteAddr = $remoteAddr;

        return $this;
    }

    /**
     * Get remoteAddr.
     *
     * @return string
     */
    public function getRemoteAddr()
    {
        return $this->remoteAddr;
    }

    /**
     * Set remoteHost.
     *
     * @param string $remoteHost
     *
     * @return Traffic
     */
    public function setRemoteHost($remoteHost)
    {
        $this->remoteHost = $remoteHost;

        return $this;
    }

    /**
     * Get remoteHost.
     *
     * @return string
     */
    public function getRemoteHost()
    {
        return $this->remoteHost;
    }

    /**
     * Set remotePort.
     *
     * @param string $remotePort
     *
     * @return Traffic
     */
    public function setRemotePort($remotePort)
    {
        $this->remotePort = $remotePort;

        return $this;
    }

    /**
     * Get remotePort.
     *
     * @return string
     */
    public function getRemotePort()
    {
        return $this->remotePort;
    }

    /**
     * Set remoteUser.
     *
     * @param string $remoteUser
     *
     * @return Traffic
     */
    public function setRemoteUser($remoteUser)
    {
        $this->remoteUser = $remoteUser;

        return $this;
    }

    /**
     * Get remoteUser.
     *
     * @return string
     */
    public function getRemoteUser()
    {
        return $this->remoteUser;
    }

    /**
     * Set redirectRemoteUser.
     *
     * @param string $redirectRemoteUser
     *
     * @return Traffic
     */
    public function setRedirectRemoteUser($redirectRemoteUser)
    {
        $this->redirectRemoteUser = $redirectRemoteUser;

        return $this;
    }

    /**
     * Get redirectRemoteUser.
     *
     * @return string
     */
    public function getRedirectRemoteUser()
    {
        return $this->redirectRemoteUser;
    }

    /**
     * Set scriptFilename.
     *
     * @param string $scriptFilename
     *
     * @return Traffic
     */
    public function setScriptFilename($scriptFilename)
    {
        $this->scriptFilename = $scriptFilename;

        return $this;
    }

    /**
     * Get scriptFilename.
     *
     * @return string
     */
    public function getScriptFilename()
    {
        return $this->scriptFilename;
    }

    /**
     * Set serverAdmin.
     *
     * @param string $serverAdmin
     *
     * @return Traffic
     */
    public function setServerAdmin($serverAdmin)
    {
        $this->serverAdmin = $serverAdmin;

        return $this;
    }

    /**
     * Get serverAdmin.
     *
     * @return string
     */
    public function getServerAdmin()
    {
        return $this->serverAdmin;
    }

    /**
     * Set serverPort.
     *
     * @param string $serverPort
     *
     * @return Traffic
     */
    public function setServerPort($serverPort)
    {
        $this->serverPort = $serverPort;

        return $this;
    }

    /**
     * Get serverPort.
     *
     * @return string
     */
    public function getServerPort()
    {
        return $this->serverPort;
    }

    /**
     * Set serverSignature.
     *
     * @param string $serverSignature
     *
     * @return Traffic
     */
    public function setServerSignature($serverSignature)
    {
        $this->serverSignature = $serverSignature;

        return $this;
    }

    /**
     * Get serverSignature.
     *
     * @return string
     */
    public function getServerSignature()
    {
        return $this->serverSignature;
    }

    /**
     * Set pathTranslated.
     *
     * @param string $pathTranslated
     *
     * @return Traffic
     */
    public function setPathTranslated($pathTranslated)
    {
        $this->pathTranslated = $pathTranslated;

        return $this;
    }

    /**
     * Get pathTranslated.
     *
     * @return string
     */
    public function getPathTranslated()
    {
        return $this->pathTranslated;
    }

    /**
     * Set scriptName.
     *
     * @param string $scriptName
     *
     * @return Traffic
     */
    public function setScriptName($scriptName)
    {
        $this->scriptName = $scriptName;

        return $this;
    }

    /**
     * Get scriptName.
     *
     * @return string
     */
    public function getScriptName()
    {
        return $this->scriptName;
    }

    /**
     * Set requestUri.
     *
     * @param string $requestUri
     *
     * @return Traffic
     */
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;

        return $this;
    }

    /**
     * Get requestUri.
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * Set phpAuthDigest.
     *
     * @param string $phpAuthDigest
     *
     * @return Traffic
     */
    public function setPhpAuthDigest($phpAuthDigest)
    {
        $this->phpAuthDigest = $phpAuthDigest;

        return $this;
    }

    /**
     * Get phpAuthDigest.
     *
     * @return string
     */
    public function getPhpAuthDigest()
    {
        return $this->phpAuthDigest;
    }

    /**
     * Set phpAuthUser.
     *
     * @param string $phpAuthUser
     *
     * @return Traffic
     */
    public function setPhpAuthUser($phpAuthUser)
    {
        $this->phpAuthUser = $phpAuthUser;

        return $this;
    }

    /**
     * Get phpAuthUser.
     *
     * @return string
     */
    public function getPhpAuthUser()
    {
        return $this->phpAuthUser;
    }

    /**
     * Set phpAuthPw.
     *
     * @param string $phpAuthPw
     *
     * @return Traffic
     */
    public function setPhpAuthPw($phpAuthPw)
    {
        $this->phpAuthPw = $phpAuthPw;

        return $this;
    }

    /**
     * Get phpAuthPw.
     *
     * @return string
     */
    public function getPhpAuthPw()
    {
        return $this->phpAuthPw;
    }

    /**
     * Set authType.
     *
     * @param string $authType
     *
     * @return Traffic
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;

        return $this;
    }

    /**
     * Get authType.
     *
     * @return string
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * Set pathInfo.
     *
     * @param string $pathInfo
     *
     * @return Traffic
     */
    public function setPathInfo($pathInfo)
    {
        $this->pathInfo = $pathInfo;

        return $this;
    }

    /**
     * Get pathInfo.
     *
     * @return string
     */
    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    /**
     * Set origPathInfo.
     *
     * @param string $origPathInfo
     *
     * @return Traffic
     */
    public function setOrigPathInfo($origPathInfo)
    {
        $this->origPathInfo = $origPathInfo;

        return $this;
    }

    /**
     * Get HostName.
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->hostName;
    }
    
    /**
     * Set HostName.
     *
     * @param string $hostName
     *
     * @return Traffic
     */
    public function setHostName($hostName)
    {
        $this->hostName = $hostName;

        return $this;
    }

    /**
     * Get origPathInfo.
     *
     * @return string
     */
    public function getOrigPathInfo()
    {
        return $this->origPathInfo;
    }
    
    /**
     * Set connectedAt.
     *
     * @param \DateTime $connectedAt
     *
     * @return Traffic
     */
    public function setConnectedAt($connectedAt)
    {
        $this->connectedAt = $connectedAt;

        return $this;
    }

    /**
     * Get $connectedAt.
     *
     * @return \DateTime
     */
    public function getConnectedAt()
    {
        return $this->connectedAt;
    }
}
